@block @block_mylastforummessages
Feature: Mylastforummessages block displays the course latest messages
  In order to be aware of the course announcements and last messages
  As a user
  I need to see the mylastforummessages block on my dashboard

  @javascript
  Scenario: Latest course message are displayed
    Given the following "users" exist:
      | username | firstname | lastname | email                |
      | student1 | Student   | 1        | student1@example.com |
    And I log in as "admin"
    And I create a course with:
      | Course full name        | Course test |
      | Course short name       | CTest       |
      | Number of announcements | 3           |
    And I enrol "Student 1" user as "Student"
    And I am on "Course test" course homepage with editing mode on
    And I add a "Forum" to section "1" and I fill the form with:
      | Forum name  | Course blog forum                               |
      | Description | Single discussion forum description             |
      | Forum type  | Standard forum displayed in a blog-like format  |
    And I add a new topic to "Course blog forum" forum with:
      | Subject | Discussion One |
      | Message | Not important |
    And I add a new topic to "Course blog forum" forum with:
      | Subject | Discussion Two |
      | Message | Not important |
    And I add a new topic to "Course blog forum" forum with:
      | Subject | Discussion Three |
      | Message | Not important |
    And I navigate to "Appearance > Default Dashboard page" in site administration
    And I add the "My last forum messages" block
    And I press "Blocks editing off"
    And I press "Reset Dashboard for all users"
    And I should see "All Dashboard pages have been reset to default."
    And I log out
    And I log in as "student1"
    And I follow "Dashboard" in the user menu
    And I should see "My last forum messages"
    Then I should see "Discussion One" in the "My last forum messages" "block"
    And I should see "Discussion Two" in the "My last forum messages" "block"
    And I should see "Discussion Three" in the "My last forum messages" "block"
    Then I log out