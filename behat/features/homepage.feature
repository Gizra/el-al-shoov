Feature: Homepage
  In order to be able to view and get info about the site
  As an anonymous user
  We need to be able to have access to the homepage

  @api @javascript
  Scenario: Visit the homepage
    Given I am an anonymous user
    When  I go to "http://www.elal.com/en/Deals/Offers/Asia/Pages/Hong-Kong-Beijing.aspx"
    And   I select deal
    Then  I should see at least one flight
