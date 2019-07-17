Feature:
  In order to tag a new Onboarder release
  As Patoche
  I want to automatically propose the new tag

  Scenario: It automatically proposes the new patch to release
    When I want to tag an already tagged branch
    Then then a new patch tag is proposed

  Scenario: It automatically proposes the first tag of a new minor release
    When I want to tag a new minor branch
    Then then a new minor tag is proposed

  Scenario: It automatically proposes the first tag of a new major release
    When I want to tag an new major branch
    Then then a new major tag is proposed
