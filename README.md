Introduction
============
My last forum messages is a plugin type block.
This plugin will display recent post of forums where user is enrol.

Required version of Moodle
==========================
This version works with Moodle version 2018112800 and above within the 3.5 branch until the
next release.

Please ensure that your hardware and software complies with 'Requirements' in 'Installing Moodle' on
'docs.moodle.org/35/en/Installing_Moodle'.

Installation
============
 1. Ensure you have the version of Moodle as stated above in 'Required version of Moodle'.  This is essential as the
    format relies on underlying core code that is out of our control.
 2. Put Moodle in 'Maintenance Mode' (docs.moodle.org/en/admin/setting/maintenancemode) so that there are no 
    users using it bar you as the administrator - if you have not already done so.
 3. Copy 'mylastforummessages' to '/blocks/' if you have not already done so.
 4. Login as an administrator and follow standard the 'plugin' update notification.  If needed, go to
    'Site administration' -> 'Notifications' if this does not happen.
 5.  Put Moodle out of Maintenance Mode.

Upgrade Instructions
====================
 1. Ensure you have the version of Moodle as stated above in 'Required version of Moodle'.  This is essential as the
    format relies on underlying core code that is out of my control.
 2. Put Moodle in 'Maintenance Mode' so that there are no users using it bar you as the administrator.
 3. In '/mylastforummessages/format/' move old 'mylastforummessages' directory to a backup folder outside of Moodle.
 4. Follow installation instructions above.
 5. If automatic 'Purge all caches' appears not to work by lack of display etc. then perform a manual 'Purge all caches'
    under 'Home -> Site administration -> Development -> Purge all caches'.
 6. Put Moodle out of Maintenance Mode.

Uninstallation
==============
 1. Put Moodle in 'Maintenance Mode' so that there are no users using it bar you as the administrator.
 2. In '/blocks/' remove the folder 'mylastforummessages' if moodle didn't.
 3. Put Moodle out of Maintenance Mode.

Version Information
===================
See Changes.md.


Us
==
Pimenko Team
