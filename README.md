# MageEnv_EInvoiceHyva

Composer package: `mageenv/module-e-invoice-hyva`
Magento module: `MageEnv_EInvoiceHyva`
License: OSL-3.0 (free)

Hyvä storefront templates for **XRechnung & ZUGFeRD E-Invoicing** (`mageenv/module-e-invoice`),
the extension for Magento that creates EN 16931 e-invoices (XRechnung 3.0 and ZUGFeRD 2.4 / Factur-X)
from invoices and credit memos.

## What it does

On Hyvä themes it renders:

- **E-invoice downloads** (ZUGFeRD PDF, XRechnung XML) on the customer's invoice and credit memo pages
  in My Account.
- The **"E-Invoices for Your Business"** fields on the account edit page: default order reference or
  Leitweg-ID, accounts payable email and preferred format.

This module contains **no business logic**. It only provides templates and a small scoped stylesheet.
Data, settings, validation, translations and access checks all come from the core module.

**Checkout:** the order reference / Leitweg-ID field works on Hyvä themes that use the Luma checkout
fallback (`hyva-themes/magento2-theme-fallback`), with no extra module. Hyvä Checkout (the separate
Magewire-based checkout) is not supported yet; customers can still save a default reference in their
account, which is used for every order.

## Requirements

| | |
|---|---|
| Core module (required) | `mageenv/module-e-invoice` `^1.0.0` |
| Hyvä | `hyva-themes/magento2-theme-module` 1.1 or later |
| Magento Open Source / Adobe Commerce | 2.4.7 to 2.4.9 |
| PHP | 8.2 to 8.5 |

## Installation

1. Install the core module from the Adobe Commerce Marketplace.
2. Install this add-on:

```bash
composer require mageenv/module-e-invoice-hyva
bin/magento module:enable MageEnv_EInvoiceHyva
bin/magento setup:upgrade
bin/magento setup:di:compile          # production mode
bin/magento setup:static-content:deploy  # production mode
bin/magento cache:flush
```

The stylesheet uses plain CSS scoped to `.mageenv-ei--hyva`, so no Tailwind rebuild is needed.

## License

Open Software License 3.0 (OSL-3.0). See `LICENSE.txt`.
