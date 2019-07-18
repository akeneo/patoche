Feature:
  In order to tag a new Onboarder release
  As Patoche
  I want to prepare the applications for deployments

  Scenario: It prepares the PIM Enterprise Cloud for a test deployment
    Given a new version of the Onboarder is going to be released
    When I want to test the PIM Onboarder bundle to be released
    Then the PIM Enterprise Cloud dependencies are updated accordingly
