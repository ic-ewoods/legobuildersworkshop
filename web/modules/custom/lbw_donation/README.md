# LEGO Builders Workshop: Donation Form

This is a demonstration of how Stripe can be used in a custom module to collect a donation.

## Context
At the time of building this demonstration, there appeared to be three via modules that could be used for setting up Stripe integration, in addition to documentation from Stripe on how to implement custom integration with PHP. These are documented on the [Stripe module options](https://github.com/woodseowl/legobuildersworkshop/wiki/Stripe-module-options) wiki page.

The current demonstration uses a hybrid of the Stripe module that is actively maintained and direct usage of the Stripe PHP library.

## Stripe Options
Stripe integration comes in two basic versions: Stripe Checkout and Stripe Elements. Stripe Elements has more options for configuration but comes with a requirement for more front-end code and style investment. This module is using Stripe Checkout primarily for the quality of UX that comes out-of-the-box.

## Module
The module is primarily a basic Drupal Form utilizing a separate set of Stripe-related classes to configure and complete the transactions. Templates are used both to isolate the Stripe code and to provide for future capabilities for configuring the form in the CMS.

### Stripe Configuration
The module depends on the [Stripe module](https://www.drupal.org/project/stripe) configuration for retrieving API keys. This is done rather than creating a new configuration for this module, as there seems to be at least modest support for other modules having dependency on the Stripe module, so these projects can share resources on a site.

To keep the context decoupled, the configuration is encapsulated into its own class [lbw_donation/src/Stripe/StripeConfig.php](https://github.com/woodseowl/legobuildersworkshop/blob/master/web/modules/custom/lbw_donation/src/Stripe/StripeConfig.php).

### Stripe Checkout
Stripe Checkout is a [simple script element](https://stripe.com/docs/checkout#integration-simple) that is added to a form and submits the form with hidden inputs set by the Stripe integration. The module delivers this with an inline template Drupal form element.

The template file approach eases the interface between Stripe-delivered code and Drupal Forms. It is also a step toward building configurable content for the CMS user because the presentation context is already parameterized.

### Stripe Charge
The Stripe Charge class has a direct depedency on the [Stripe PHP library](https://github.com/stripe/stripe-php). Presently the usage is very simplistic, primary isolating the configuration and error trapping. This was written directly against the library because the Stripe module did not have isolation for Stripe Charge actions.

Addressing these limitations in the Stripe module would be an excellent opportunity for contributing back to the community. The [Stripe API module](https://www.drupal.org/project/stripe_api), which is minimally maintained, has some good approaches to providing the Stripe API, but it is overlapping with the Stripe module. It seems the intent of the Stripe module is to serve as an API ("being the API that other contribs can use"), but it is lacking in the use case tackled in lbw_donation. If this project was to go further, it seems contributing to the Stripe module rather than continuing to build yet another interface to the Stripe PHP library would be worhtwhile.


## To Do
- Set up multiple Stripe Checkout buttons for simple "$5", "$10" kind of call-to-action options. This should only require a small amount of JS to hook each Stripe Checkout button to setting the donation amount.
- Create a custom block that can accept a description field and a list of donation values and can be used to place the donation form through standard block management.
- Add CSS theming for the form
- Re-review Stripe dependencies to determine future best practices for Stripe library usage and Stripe module usage. The current implementation was very MVP-only.
- Since this is demonstration, there are a few "src/Test" classes and supporting routes and templates that remain simply for discussion about process but would be removed under normal delivery of the project.
- Test in a production environment
