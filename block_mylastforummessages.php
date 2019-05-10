<?php
    // This file is part of Moodle - http://moodle.org/
    //
    // Moodle is free software: you can redistribute it and/or modify
    // it under the terms of the GNU General Public License as published by
    // the Free Software Foundation, either version 3 of the License, or
    // (at your option) any later version.
    //
    // Moodle is distributed in the hope that it will be useful,
    // but WITHOUT ANY WARRANTY; without even the implied warranty of
    // MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    // GNU General Public License for more details.
    //
    // You should have received a copy of the GNU General Public License
    // along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

    /**
     * This file contains the news item block class, based upon block_base.
     * @package    block_mylastforummessages
     * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
     * @copyright  2018 DigiDago
     * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
     */

    /**
     * Class block_mylastforummessages
     * @package    block_mylastforummessages
     * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
     * @copyright  2018 DigiDago
     * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
     */
    class block_mylastforummessages extends block_base {
        function init() {
            $this->title = get_string( 'pluginname' , 'block_mylastforummessages' );
        }

        function has_config() {
            return true;
        }

        function get_content() {
            global $CFG , $OUTPUT , $DB , $USER;

            if ($this->content !== NULL) {
                return $this->content;
            }

            $this->content         = new stdClass;
            $this->content->text   = '';
            $this->content->footer = '';

            if (empty( $this->instance )) {
                return $this->content;
            }

            if ($this->page->course->newsitems) {   // Create a nice listing of recent postings

                require_once( $CFG->dirroot . '/mod/forum/lib.php' );   // We'll need this

                $text = '';
                $config = get_config('block_mylastforummessages');
                if ($config->onlyannoucement) {
                    $forums = $DB->get_records( "forum" , ['type' => 'news'] , 'id ASC' );
                } else {
                    $forums = $DB->get_records( "forum" , null , 'id ASC' );
                }
                if (!$forums) {
                    return '';
                }

                $discussions = [];
                $sort        = forum_get_default_sort_order( true , 'p.modified' , 'd' , false );

                foreach ($forums as $forum) {
                    /// Get all the recent discussions we're allowed to see
                    $context = context_course::instance( $forum->course );
                    if (!is_enrolled( $context , $USER->id , '' , true )) {
                        continue;
                    }

                    // This block displays the most recent posts in a forum in
                    // descending order. The call to default sort order here will use
                    // that unless the discussion that post is in has a timestart set
                    // in the future.
                    // This sort will ignore pinned posts as we want the most recent.

                    $modinfo = get_fast_modinfo( $forum->course );
                    if ($modinfo) {
                        $cm = $modinfo->instances['forum'][$forum->id];
                        if ($cm) {
                            if (!$cm->uservisible) {
                                continue;
                            }

                            $context = context_module::instance( $cm->id );

                            /// User must have perms to view discussions in that forum
                            if (!has_capability( 'mod/forum:viewdiscussion' , $context )) {
                                continue;
                            }

                            $newdiscussions = forum_get_discussions( $cm , $sort , false , -1 , null , false , -1 , 0 ,
                                                                     FORUM_POSTS_ALL_USER_GROUPS
                            );

                            foreach ($newdiscussions as $key => $discussion) {
                                $discussion->subject = $discussion->name;
                                $discussion->subject = format_string( $discussion->subject , true , $forum->course );
                            }
                            $discussions = array_merge( $discussions , $newdiscussions );
                        }
                    }
                }

                if (!$discussions) {
                    $text                .= '(' . get_string( 'nonews' , 'forum' ) . ')';
                    $this->content->text = $text;
                    return $this->content;
                }

                /// Actually create the listing now

                $strftimerecent = get_string( 'strftimerecent' );
                foreach ($discussions as $key => $discussion) {
                    // we want to change order and select x recent
                    $discussions[$discussion->modified] = $discussion;
                    unset( $discussions[$key] );
                }
                $displaypostnumber = $config->displaypostnumber;
                $messagelenghtmax = $config->messagelenghtmax;
                krsort( $discussions );
                $discussions = array_slice( $discussions , 0 , $displaypostnumber );

                /// Accessibility: markup as a list.
                $text .= "\n<ul class='unlist'>\n";
                foreach ($discussions as $key => $discussion) {
                    $discussionmessage   = $DB->get_record_sql( "SELECT message as message FROM {forum_posts} WHERE id = :id" ,
                                                                [ 'id' => $discussion->id ]
                    );
                    $discussion->message = $discussionmessage->message;
                    if (strlen( $discussion->message ) > $messagelenghtmax) // if you want...
                    {
                        $maxLength           = $messagelenghtmax - 1;
                        $discussion->message = substr( $discussion->message , 0 , $maxLength );
                        $discussion->message .= ' [...]';
                    }
                    $user = $DB->get_record( 'user' , [ 'id' => $discussion->userid ] );
                    $text .= '<li class="post">' . $OUTPUT->user_picture( $user , [ 'size' => 60 ] )
                             . '<div class="head clearfix"><div class="info"><a class="title" href="' . $CFG->wwwroot
                             . '/mod/forum/discuss.php?d=' . $discussion->discussion . '">' . $discussion->subject
                             . '</a><div class="name">' . fullname( $discussion ) . ' - '
                             . userdate( $discussion->modified , $strftimerecent ) . '</div>' . '<div class="message">'
                             . format_text( $discussion->message ) . '</div>' . '</div></div>' . "</li>\n";
                }
                $text .= "</ul>\n";

                $this->content->text = $text;

            }

            return $this->content;
        }
    }


