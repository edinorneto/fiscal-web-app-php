# 💼 Cadastro Fiscal & Consulta Tributária — PHP Web App

> **Disclaimer:** This project uses **synthetic (fictional) tax rules and rates** for learning and portfolio purposes only. Do not use for real tax compliance. Always consult a tax specialist.

---

## 🔗 Context: Evolution of a Python CLI Project

This project is the **web evolution** of a previous terminal-based Python solution:

👉 **[cadastro-consulta](https://github.com/edinorneto/cadastro-consulta)** — the original Python CLI version

The same fiscal domain (product registration + tax consultation for NF-e) was rebuilt from scratch in PHP as a multi-file web application, with a complete CRUD layer, browser interface, and a more explicit separation of concerns — demonstrating the transition from a functional CLI script to a structured web backend.

**What changed architecturally:**

| Python CLI | PHP Web App |
|---|---|
| Terminal input/output | Browser-based multi-screen interface |
| Single-pass execution | Stateful request/response cycle |
| JSON R/W via simple functions | Isolated data layer (`data.php`) |
| Logic concentrated in modules | Process file per user action (`process_*.php`) |
| No visual layer | Custom CSS design system (dark fintech theme) |

---

## 🎯 What This Project Does

A PHP 8.x web application for product registration and fiscal consultation oriented to invoice issuance (NF-e). The system allows registering products and automatically retrieving the correct tax data (ICMS, IPI, PIS, COFINS) based on the tax regime and destination region — entirely through a browser interface with no external frameworks.

---

## 📁 Project Structure

```
├── index.php               # Main menu: register or consult
├── cadastro.php            # HTML form — register a new product
├── process_cadastro.php    # Receives POST, validates, saves to JSON
├── produtos.php            # Product listing with edit/deactivate actions
├── editar.php              # Edit form for existing products
├── process_editar.php      # Handles product update
├── process_apagar.php      # Handles product deletion
├── process_status.php      # Toggles product active/inactive status
├── consulta.php            # Product selection + regime and region form
├── process_consulta.php    # Crosses product + regime + region → returns fiscal data
├── tax_rules.php           # Associative array: fictional tax rules (ICMS, IPI, PIS, COFINS)
├── config.php              # Global constants (JSON path, settings)
├── data.php                # Isolated read/write functions for JSON persistence
├── style.css               # Full design system (dark fintech theme, no frameworks)
├── cadastro_produtos.json  # Persistent product database (auto-generated)
└── screenshots/            # Visual documentation
```

---

## 🏗️ Architecture Decisions

**Process-per-action pattern:** Each user action has its own `process_*.php` file responsible for receiving POST data, validating input, executing the business logic, and redirecting. This mirrors a controller-like responsibility without relying on a framework — making the request flow explicit and traceable.

**Isolated data layer:** `data.php` is the only file that reads or writes to `cadastro_produtos.json`. No other file touches persistence directly — any change to the storage format is contained in a single place.

**Rule engine as data:** `tax_rules.php` exposes an associative array keyed by `[regime][region]`. Adding a new fiscal rule means extending the array without modifying the consultation logic — open for extension, closed for modification.

---

## 🎯 Business Rules (Fictional Simulation)

### Product Registration (Full CRUD)

- **Create:** name, description, price, category, stock, unit, NCM (8 digits), active status
- **Read:** product list with status indicator and action buttons
- **Update:** editable fields with re-validation
- **Delete / Toggle status:** soft deactivation or permanent removal
- Auto-generated: sequential ID, registration timestamp (America/Sao_Paulo)

### Fiscal Consultation (Tax Rule Engine)

- User selects: product + tax regime + destination region
- System crosses this data against `tax_rules.php` to retrieve:
  - **CFOP** — Fiscal Operation Code
  - **CST** — Tax Situation Code
  - **ICMS Taxation Code**
  - **Tax rates:** ICMS, IPI, PIS, COFINS
  - **Legal description** of the applied rule

### Tax Regimes (Fictional)

- **Convênio XX** — simulates exemption (internal) and base reduction (interstate)
- **TTD XX** — simulates deferral for all destinations

### Destination Regions

- Internal: SC
- Interstate: PR, RS, MT, MS

---

## ✅ Requirements

- PHP **8.x**
- Write permission on the project folder (for JSON persistence)

---

## 🚀 Getting Started

```bash
git clone https://github.com/edinorneto/tax-rule-php.git
cd tax-rule-php
php -S localhost:8000
```

Open `http://localhost:8000` in your browser.

---

## 🧪 Example Flow

1. **Register a product:**
   - Name: `Ureia Agrícola` | NCM: `31021010` | Price: R$ 1.500,00 | Stock: 1000 kg

2. **Consult for invoice:**
   - Product: `Ureia Agrícola` | Regime: `Convênio XX` | Destination: `PR`

3. **System returns:**

```
CFOP:       6102 – Venda interestadual nacional
CST:        7 – Importada sem similar nacional
Cód. Trib.: 020
ICMS: 6,0%  |  IPI: 0%  |  PIS: 0%  |  COFINS: 0%
```

---

## 🧠 Concepts Applied

`PHP 8.x` · `HTML5` · `CSS3` · `CRUD Design` · `Rule Engine` · `Multi-file Architecture` · `Separation of Concerns` · `Process-per-action Pattern` · `JSON Persistence` · `Null Coalescing ??` · `Git & GitHub`

---

## 👨‍💻 Author

**Edinor de Souza Neto**
[LinkedIn](https://www.linkedin.com/in/edinor-de-souza-neto/) · [GitHub](https://github.com/edinorneto)

See the origin of this project: [cadastro-consulta](https://github.com/edinorneto/cadastro-consulta) (Python CLI version)
