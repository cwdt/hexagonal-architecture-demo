Feature:

  Scenario: Change status to accepted at daytime
    Given the sunrise is at "7" and the sunset is at "21"
    When I change the status to "allowed" from "2017-01-01 10:00" until "2017-01-01 14:00"
    Then the state at "2017-01-01 11:00" will be "allowed"
    Then the state at "2017-01-01 17:00" will be "unknown"
    Then the state at "2017-01-01 22:00" will be "unknown"

  Scenario: Change status to accepted at night time
    Given the sunrise is at "7" and the sunset is at "21"
    When I change the status to "allowed" from "2017-01-01 06:00" until "2017-01-01 10:00"
    Then the validation for the field "from" will fail with the message "sunrise"