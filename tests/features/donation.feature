Feature: Donation Page

  Scenario: Donation Test Page is Reachable
    Given I am at "/lbw_donate/test"
    Then I should see text matching "Donate Test Complete"
