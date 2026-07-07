# Guía de inicio — Tromay

**Para:** Cajeros y administradores de Tromay Casas de Cambio
**URL:** https://tromay.kapitalya.com.bo

---

## Acceso al sistema

1. Abre tu navegador y ve a `tromay.kapitalya.com.bo`
2. Inicia sesión con tu usuario y contraseña
3. Si olvidaste tu contraseña, contacta al administrador del sistema

---

## Consultar tasas de cambio (API pública)

Las tasas son accesibles sin contraseña para clientes externos:

```
https://tromay.kapitalya.com.bo/api/rates
```

Ejemplo de respuesta:
```json
{
  "data": [
    { "id": 1, "name": "usd", "buy": 6.92, "sell": 7.10, "oficial": 6.86 },
    { "id": 2, "name": "eur", "buy": 7.50, "sell": 7.70, "oficial": 7.42 }
  ],
  "disclaimer": "Tasas referenciales. No representan cotizaciones oficiales.",
  "updated_at": "2026-06-13T10:30:00-04:00"
}
```

---

## Actualizar tasas de cambio

1. Inicia sesión como **administrador**
2. Ve al menú **Tasas** → **Gestionar tasas**
3. Haz clic en la divisa que quieres actualizar
4. Ingresa los valores de **Compra**, **Venta** y **Oficial**
5. Haz clic en **Guardar** — la API se actualiza automáticamente en 5 minutos

> Las tasas se actualizan en tiempo real para los sistemas que consumen la API
> (serguicv.com y exchange-alert-bolivia).

---

## Registrar una transacción

1. Ve al menú **Transacciones** → **Nueva transacción**
2. Selecciona el **cliente** (busca por nombre o CI)
3. Elige la **divisa** y el **monto**
4. Selecciona el **tipo**: Compra (el cliente nos vende) o Venta (el cliente nos compra)
5. Verifica el monto en bolivianos calculado automáticamente
6. Haz clic en **Confirmar transacción**

---

## Registrar un cliente nuevo

1. Ve a **Clientes** → **Nuevo cliente**
2. Completa los datos requeridos:
   - **Nombre completo** (como aparece en el CI)
   - **Número de CI** (Cédula de Identidad boliviana)
   - **Teléfono** (opcional pero recomendado)
3. Si el cliente aparece en listas PEP o con restricciones, marca la casilla correspondiente
4. Haz clic en **Guardar**

---

## Ver reportes

1. Ve al menú **Reportes**
2. Selecciona el período (hoy, semana, mes)
3. Puedes exportar a **PDF** o **Excel**

---

## Cierre de turno

1. Ve a **Turnos** → **Cerrar turno**
2. Verifica el resumen de transacciones del día
3. Confirma los montos en caja
4. Haz clic en **Cerrar turno** — se genera el reporte de cierre

---

## Preguntas frecuentes

**¿Por qué la API dice "Tasas referenciales"?**
Las tasas que publicamos son referenciales. El tipo de cambio real de operación puede variar según el monto y la negociación.

**¿Con qué frecuencia se actualizan las tasas en la API?**
El sistema cache las tasas por 5 minutos. Si actualizas una tasa, tardará máximo 5 minutos en reflejarse en la API pública.

**¿Qué hago si la API no responde?**
Contacta al administrador de sistemas. El SLA de recuperación es menos de 15 minutos.

---

**Soporte:** sergio.denis.troche.mayta@gmail.com · WhatsApp +591-XXXXXXXX
