# Greenplus WaaS WHMCS Module

The Greenplus WaaS module is a WHMCS module that allows you to integrate with the GreenPlus WaaS (WordPress as a Service) API to manage WordPress installations. This module provides the ability to create new WordPress installations, terminate existing installations, and retrieve available plans.

## Installation

To install the Greenplus WaaS module, follow these steps:

1. Download the module files to your WHMCS server.
2. Upload the module files to the following location in your WHMCS installation: `/modules/servers/greenplusWaaS`.
3. Make sure to set appropriate permissions for the module files and directories.

## Configuration

Once the module is activated, you need to configure it with the required settings.

### Configuration Options

The following configuration options are available for the Greenplus WaaS module:

- **Plans**: Select the desired plan for the WordPress installation from the available options.

Please note that the available plan options are retrieved from the GreenPlusProvider using the GreenPlusProvider::getSpecs() method.

## Usage

### Creating a New WordPress Installation

To create a new WordPress installation using the Greenplus WaaS module, follow these steps:

1. Navigate to WHMCS Admin Area > Clients > Products/Services.
2. Click on "Add New Product."
3. Select the appropriate group and module for the product.
4. Enter the required product details, including the desired plan from the "Plans" configuration option.
5. Complete the product creation process.

### Terminating an Existing WordPress Installation

To terminate an existing WordPress installation using the Greenplus WaaS module, follow these steps:

1. Navigate to WHMCS Admin Area > Clients > Products/Services.
2. Locate the WordPress installation you want to terminate.
3. Click on "Terminate" to initiate the termination process.

## API Version

The Greenplus WaaS module uses API version 0.1 for communication with the GreenPlus WaaS API.

## Requirements

- WHMCS version 7 or later.
- GreenPlus WaaS API access and valid credentials.

## Troubleshooting

If you encounter any issues or errors while using the Greenplus WaaS module, please check the following:

1. Ensure that the `.env` file is present in the module directory and contains valid credentials for the GreenPlus WaaS API.
2. Verify that the API endpoint URL is correct and accessible from the WHMCS server.
3. Check the module logs for any error messages or exceptions.

If you need further assistance, please contact our support team.

## License

The Greenplus WaaS WHMCS module is licensed under the [MIT License](LICENSE).

---
_This document was last updated on 2023/7/31._
