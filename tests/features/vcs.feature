Feature:
  In order to tag a new Onboarder release
  As Patoche
  I want to clone the Onboarder repositories

  Scenario: It clones the Supplier Onboarder vcs repository
    Given a new version of the Onboarder is going to be released
    When I clone the onboarder vcs repository
    Then the onboarder project is available locally
