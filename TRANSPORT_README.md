# Transport Fee Management Module

This document outlines the implementation of the Transport Fee Management module.

## 1. Student Registration (Admin)
-   **View**: `School Admin -> Create Student`
-   **Features**: Select Route, Pickup Point, Start Month.
-   **Logic**: Saves to `student_transport` table.

## 2. Fee Generation (Accountant)
-   **Option A**: "Generate Bulk Challan" (Purple Button). Integrating Transport + Tuition in one invoice.
-   **Option B**: "Generate Transport Fees" (Amber Button). Separate invoice for Transport only.

## 3. Student Dashboard
-   **Widget**: Added "Transport" widget showing Route and Monthly Fee.
-   **Fees**: Transport fees appear in the "Fees Due" card.
