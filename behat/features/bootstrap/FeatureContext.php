<?php

use Drupal\DrupalExtension\Context\MinkContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;

class FeatureContext extends MinkContext implements SnippetAcceptingContext {

  /**
   * @Given I am an anonymous user
   */
  public function iAmAnAnonymousUser() {
    // Just let this pass-through.
  }

  /**
   * @When I visit the homepage
   */
  public function iVisitTheHomepage() {
    $this->getSession()->visit($this->locatePath('/'));
  }

  /**
   * @Then I should have access to the page
   */
  public function iShouldHaveAccessToThePage() {
    $this->assertSession()->statusCodeEquals('200');
  }

  /**
   * @Then I should not have access to the page
   */
  public function iShouldNotHaveAccessToThePage() {
    $this->assertSession()->statusCodeEquals('403');
  }

  /**
   * @When I select first deal
   */
  public function iSelectFirstDeal() {
    $this->iWaitForCssElement('#hotDealSlider0_overflow');

    $hotDeals = $this->getSession()->getPage()->find('css', '#hotDealSlider0_overflow > div > div > a:first-child' );
    $this->getSession()->wait(2000);
    $hotDeals->click();

    $this->iWaitForCssElement('.dealBox');
    if (null === $this->assertElementOnPage('.dealBox_orderBtn > a')) {
      $link = $this ->getSession()->getPage()->find('css','.dealBox_orderBtn > a');
      $this->getSession()->wait(2000);
      $link -> click();
    }

    else {
      $this->iWaitForCssElement('#moreDeals0_overflow');

      $theDeal = $this->getSession()->getPage()->find('css', '#moreDeals0_overflow > div > div a:first-child' );
      $this->getSession()->wait(2000);
      $theDeal->click();
    }
  }

  /**
   * @Then I should see at least one flight
   */
  public function iShouldSeeAtLeastOneFlight() {
    // Select browser tab.
    $windowNames = $this->getSession()->getWindowNames();
    if(count($windowNames) > 1) {
      $this->getSession()->switchToWindow($windowNames[1]);
    }

    $this->iWaitForCssElement('.ui-datepicker-calendar');
    $this->assertElementOnPage('.highlight');
    $this->getSession()->wait(3000);
  }

  /**
   * @Then /^I wait for css element "([^"]*)" to "([^"]*)"$/
   */
  public function iWaitForCssElement($element, $appear = 'appear') {
    $xpath = $this->getSession()->getSelectorsHandler()->selectorToXpath('css', $element);
    $this->waitForXpathNode($xpath, $appear == 'appear');
  }

  /**
   * Helper function; Execute a function until it return TRUE or timeouts.
   *
   * @param $fn
   *   A callable to invoke.
   * @param int $timeout
   *   The timeout period. Defaults to 10 seconds.
   *
   * @throws Exception
   */
  private function waitFor($fn, $timeout = 15000) {
    $start = microtime(true);
    $end = $start + $timeout / 1000.0;
    while (microtime(true) < $end) {
      if ($fn($this)) {
        return;
      }
    }
    throw new \Exception('waitFor timed out.');
  }

  /**
   * Wait for an element by its XPath to appear or disappear.
   *
   * @param string $xpath
   *   The XPath string.
   * @param bool $appear
   *   Determine if element should appear. Defaults to TRUE.
   *
   * @throws Exception
   */
  private function waitForXpathNode($xpath, $appear = TRUE) {
    $this->waitFor(function($context) use ($xpath, $appear) {
      try {
        $nodes = $context->getSession()->getDriver()->find($xpath);
        if (count($nodes) > 0) {
          $visible = $nodes[0]->isVisible();
          return $appear ? $visible : !$visible;
        }
        return !$appear;
      }
      catch (WebDriver\Exception $e) {
        if ($e->getCode() == WebDriver\Exception::NO_SUCH_ELEMENT) {
          return !$appear;
        }
        throw $e;
      }
    });
  }


}
