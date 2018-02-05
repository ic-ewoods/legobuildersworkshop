Feature: Donation Page

  Scenario: Donation Page is Reachable
    Given I am at "/donate"
    Then I should see text matching "Donation Form"

  Scenario: Donation Page Description Template is Displayed
    Given I am at "/donate"
    Then I should see text matching "Help support"
