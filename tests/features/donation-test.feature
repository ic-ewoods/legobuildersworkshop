Feature: Donation Test Pages

  Scenario: Donation Test Page is Reachable
    Given I am at "/lbw_donate/test/content"
    Then I should see text matching "Donate Test Complete"

  Scenario: Donation Test Stripe Form is Reachable
    Given I am at "/lbw_donate/test/stripe_form"
    Then I should see 2 "form" elements

  Scenario: Donation Template Test Page is Reachable
    Given I am at "/lbw_donate/test/template_form"
    Then I should see text matching "Twig template content for lbw_donation."
