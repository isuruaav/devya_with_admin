---
paths:
  - 'app/Filament/Pages/**'
---

# Pages

## Avoid double-counting appointment income
Consultation doctor fees are already represented in consultation-bill totals. When reporting appointment income separately, add only the distinct `facility_service_fee`; include appointment facility fees and reception bills by paid status and `paid_at` so unpaid bills are not reported as collected income.
