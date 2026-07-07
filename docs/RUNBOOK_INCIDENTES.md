# Runbook de Incidentes — Tromay

**URL:** https://tromay.kapitalya.com.bo (acceso via Tailscale)
**Namespace K8s:** `private`
**Pods normales:** 2 (tromay-web, mysql-tromay)

---

## Diagnóstico rápido

```bash
kubectl get pods -n private | grep tromay
kubectl logs -n private deployment/tromay --tail=30
kubectl top pod -n private | grep tromay
```

---

## Incidente 1 — El sitio no responde (500 / sin conexión)

```bash
# Estado de pods
kubectl get pods -n private | grep tromay

# Si está en CrashLoopBackOff:
kubectl logs -n private deployment/tromay --previous | tail -30
# Causas comunes: APP_KEY no configurada, MySQL caído, .env faltante

# Reiniciar el pod
kubectl rollout restart deployment/tromay -n private
kubectl rollout status deployment/tromay -n private --timeout=120s

# Rollback si el restart no ayuda
kubectl rollout undo deployment/tromay -n private

# Verificar health tras reinicio
curl -s http://tromay-service.private.svc.cluster.local/api/rates | jq '.data | length'
```

---

## Incidente 2 — MySQL no responde

```bash
# Ver estado de MySQL
kubectl get pods -n private | grep mysql-tromay
kubectl logs -n private statefulset/mysql-tromay --tail=20

# Reiniciar MySQL (si está Running pero no responde)
kubectl rollout restart statefulset/mysql-tromay -n private

# Verificar conexión desde el pod de tromay
kubectl exec -n private deployment/tromay -- \
  php artisan tinker --execute="DB::connection()->getPdo(); echo 'OK';"

# Ver queries lentas en MySQL
kubectl exec -n private statefulset/mysql-tromay -- \
  mysql -u root -p"$MYSQL_ROOT_PASSWORD" -e \
  "SELECT user, host, time, state, info FROM information_schema.processlist WHERE time > 5;"
```

---

## Incidente 3 — API /api/rates devuelve datos obsoletos o vacíos

La API usa cache de Laravel (5 minutos). Si las tasas no se actualizan:

```bash
# Limpiar cache de Laravel manualmente
kubectl exec -n private deployment/tromay -- \
  php artisan cache:clear

# Verificar que los registros Cash tienen status=1 en MySQL
kubectl exec -n private statefulset/mysql-tromay -- \
  mysql -u tromay_user -p"$MYSQL_PASSWORD" tromay_db -e \
  "SELECT id, name, buy, sell, oficial, status, updated_at FROM cash WHERE status=1 ORDER BY id;"

# Si la tabla está vacía, sembrar datos demo
kubectl exec -n private deployment/tromay -- \
  php artisan db:seed --class=DemoDataSeeder
```

---

## Incidente 4 — Rate limiting excesivo (usuarios bloqueados)

```bash
# Ver configuración de throttle
kubectl exec -n private deployment/tromay -- \
  php artisan tinker --execute="
  \$key = 'throttle:api:' . request()->ip();
  echo cache(\$key, 0) . ' requests en ventana actual';
"

# Limpiar rate limit para una IP específica (emergencia)
kubectl exec -n private deployment/tromay -- \
  php artisan tinker --execute="
  cache()->forget('throttle:api:IP_AQUI');
  echo 'Rate limit limpiado';
"

# Para aumentar el límite temporalmente, editar config/throttle o ajustar via ConfigMap
```

---

## Incidente 5 — Migración o actualización rota

```bash
# Ver estado de migraciones
kubectl exec -n private deployment/tromay -- \
  php artisan migrate:status

# Si hay migraciones pendientes
kubectl exec -n private deployment/tromay -- \
  php artisan migrate --force

# Rollback de la última migración si hay problemas
kubectl exec -n private deployment/tromay -- \
  php artisan migrate:rollback --force

# Ver logs de artisan
kubectl logs -n private deployment/tromay | grep "artisan\|migrate\|error" | tail -20
```

---

## Escalación

**WhatsApp Sergio:** +591-XXXXXXXX
**Email:** sergio.denis.troche.mayta@gmail.com
**SLA recovery:** < 15 minutos
**Incluir en el mensaje:** estado de pods, últimas 20 líneas de logs, cuándo empezó el problema.
