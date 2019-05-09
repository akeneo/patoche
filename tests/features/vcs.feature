Feature:
  In order to tag a new Onboarder release
  As Patoche
  I want to download the Onboarder repository archives

  Scenario: It downloads the Supplier Onboarder archive
    Given a new version of the Onboarder is going to be released
    When I download the onboarder archive
    Then the onboarder project is available locally
