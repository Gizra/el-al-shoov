Feature: Homepage
  In order to be able to view and get info about a deal.
  As an anonymous user
  We need to be able to select a deal and get access to order flight

  @api @javascript
  Scenario: Visit the asia deals
    Given I am an anonymous user
    When  I go to "http://www.elal.com/en/Deals/Offers/Asia/Pages/Hong-Kong-Beijing.aspx"
    And   I select deal
    Then  I should see at least one flight
