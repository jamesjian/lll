<?php
/**
 *
 * 
 1. user
  inactive: when register a new user, or its email box is changed (need to activate it again)
  active: when activate the email box, or enable it by admin from disable status.
  registered: after registration and before activation
  disabled: by admin, when this user login or do anything, the software will ask it to contact the admin. 

 * 
 * difference between inactive an disabled: the former can be activated, the latter is disabled by admin and cannot do anything.
    0. inactive 	//be disabled
    1. active           //after activation
    2. registered       //after registration
    3. deleted  (not implemented)
 * 
 * 
 *2. question  when status=2
 * valid 1 valid, 0 invalid
 * status 0: inactive by user, 1: active, 2: disabled by admin (because of invalid)
 * 
 */