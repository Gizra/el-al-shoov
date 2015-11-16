Feature: Homepage
  In order to be able to view and get info about a deal.
  As an anonymous user
  We need to be able to select a deal and get access to order flight

  @javascript
  Scenario: Visit a deal
    Given I am an anonymous user
    When  I go to homepage
    And   I select first deal
    Then  I should see at least one flight
