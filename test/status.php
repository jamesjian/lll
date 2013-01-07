<?php
/**
 * * minimum test:
 * admin:
 * 1. user list  , disable
 * 2. question list, disable
 *    question by user
 * 3. answer list, disable
 *    answer by question, by user
 * 4. ad list, disable
 *    ad by user
 *    answer link ad
 * 5. claim list, update
 * 6. page crud
 *    
 * front: 
 * 1. user list ((user/all/page number), register, login, forgotten password, activate
 * 2. question create, list, list by user/tag, content  (contain answer and ad)
 *    claim, vote
 * 3. answer create, claim, vote
 * 4. ad list, ad content, claim, vote
 * 5. static pages
 * 6. contact us
 * 
 * user:
 * 1. profile, change password, 
 * 2. question list, edit (when no answer)
 * 3. answer list, edit 
 * 4. ad, crud
 *    link ad and answer
 * 5. claim list if have claim
 *
 1. user
  inactive: when register a new user, or its email box is changed (need to activate it again)
  active: when activate the email box, or enable it by admin from disable status.
  registered: after registration and before activation
  disabled: by admin, when this user login or do anything, the software will ask it to contact the admin. 

 * difference between inactive an disabled: the former can be activated, the latter is disabled by admin and cannot do anything.
    0. inactive 	//be disabled, but question and answer is not influenced, ads will be disabled too,don't use it frequently
    1. active           //after activation
    2. registered       //after registration
    //3. deleted          //will never be back to active
     

 * 
 *2. question  cannot be deleted by user, only be deactivated by user or disabled by admin 
 * when status=2
 * valid 0 not sure (can be claimd, has claim button), 1 valid(cannot be claimd, no claim button), 2 invalid(has been verified as an invalid question, no display at all)
 * status 0: inactive by user only when no answer, 1: active, 2: disabled by admin (because of invalid, valid=2)
 *                status                            valid    display
 * value          0 (when no answer)                 any     content   no display        
 *               1                                   0       has report button
 *               1                                   1       no report button
 *               2                                   any     content  no display
 * 
 * 3. answer 
 * 
 * 4. ad
 *   status: 0: inactive by user, will not displayed
 *           1: active 
 *           2. disabled by admin, will not displayed
 * date_start start from answer connect to ad or extend
 * search question: search title and tag 
 * homepage:
 * 
 * main menu:
 * 0. index.php   latest 100 questions, no pagination
 * 1. question/latest/pageid questions (最新问题， order on date_created desc）, 
 *    首页->最新
 *    one question: front/question/content/questionid
 *    tag:   front/question/tag/tagid
 * 2. question/answered/pageid   solved questions （已回答） where num_of_answer>0 on vote (of question), date_created） 
 *     首页->已回答
 * 3. question/unanswered/pageid unanswered questions （无回答where num_of_answer=0 on vote (of question), date_created）,
      首页->未回答
 * 4. question/popular/pageid most populars questions （最受关注 on vote  desc, num_of_views desc, date_created  desc） 
 *    首页->最受关注
 * 5. question/tag/tagid/latest/pageid  order by date_created
 *   首页->教育(always latest)
 * 6. question/tag/tagid/unanswered/pageid  order by date_created where num_of_answers=0
 * 首页->教育(always latest)->未回答
 * 7. question/tag/tagid/answered/pageid  order by date_created where num_of_answers>0
 *   首页->教育(always latest)->已回答
 * 8. question/tag/tagid/popular/pageid  order by num_of_votes
 *   首页->教育(always latest)->最受关注
 * 9. tag/qpopular/pageid   most popular category (order on num_of_questions)
 *    首页->分类(always popular)
 * 10. tag/qpinyin/pageid all category  (order on pinyin asc)
       首页->分类(always popular)->拼音顺序
 * 11. user/all/pageid    user list  (order by answers)
 *    首页->用户 （always order by num of answers)
 * 12. user/detail/userid  user page (have links to question, answer and ad)
 *     首页->用户 （always order by num of answers)->james
 * 13.   question/user/userid/pageid
 *     首页->用户 （always order by num of answers)->james->问题
 * 14.   answer/user/userid/pageid
 *     首页->用户 （always order by num of answers)->james->回答
 * 15.   ad/user/userid/pageid
 *     首页->用户 （always order by num of answers)->james->广告
 * 16. tag/apopular/pageid
 *   首页->广告分类(always popular)
 * 17.    tag/apinyin/pageid
 *    首页->广告分类(always popular)->拼音顺序
 * 
 * 18. ad/tag/tagid/pageid
 *    首页->教育（广告）(always score and not expired)
 * 19. question/content/questionid
 *    (breadcrumb)stack->NIBA是什么？
 * 20. ad/content/adid
 *    (breadcrumb)stack->赶快来报名    
 * 21. question/create
 * 22. answer/create/questionid
 * 
 * vote and claim only by user
 * 23. user/vote/question/questionid
 * 24. user/vote/answer/answerid
 * 25. user/vote/ad/adid
 * 26. user/claim/question/questionid
 * 26. user/claim/answer/answerid
 * 26. user/claim/ad/adid
 * 
 * 
 * 27. question/myquestions
 * 28. answer/myanswers
 * 29. ad/myads
 * 30. ad/create
 * 31. ad/delete
 * 32. ad/update
 * 33. user/change_password
 * 34. user/home  (name, email, logo, num_of_question/answer/ad, scores)
 * 35. user/change_logo
 * 
    vote and claim ajax
 *  *  displays ad categories (rather than ads), can switch between most popular categories and all categories for ads
 *     and region
 * 
 * num of ads = num of answers, if an invalid ads occurred, num of answers will be substracted by 1
 * sigma (weight of ads) = num of answers  - num of invalid ads
 * email and user name cannot be changed
 * 
 * 
 * admin:
 * 1. user/retrieve/cretae/delete/update/change_status/reset_password
 * 2. question/retrieve/create/delete/update
 *    question/retrieve_by_uid/uid
 * 3. answer/retrieve/create/delete/update
 *    answer/retrieve_by_qid/qid
 *    answer/retrieve_by_uid/uid
 * 4. ad/retrieve/create/delete/update
 *    ad/retrieve_by_uid/uid
 *    ad/retrieve_by_aid/aid
 * 5. tag/retrieve/create/delete/update/merge
 * 
 * TEST:
 * 1. all pages
 * 2. prepare:
 *   10 users
 *   100 questions
 *   200 answers, each question has 3-10 answers
 *   150 ads to answers
 * 
 * 
 * 10. 
 * 1-4 question pages use same format: question list(50 records), most popular categories(top 20), 
 * 5-6 category pages use similar format, must have search
 * 7. ads page always under category, no region options
 *    order by weight desc, date_created asc
 * 
 * ad: region, category, weight, date,  (decided by user, for example number of ads is 1000, an accountant ad can have weight 1000 to make sure it's always in the top of list)
 * region is always an attribute
 * order by date_created desc, num_of_view desc, 
 * when the content of a question/answer/ad changed, statistics will be cleared
 * but when extend, will not clear statistics
 * ad validation period: 30 days
 * if no ad for an answer, use a random ad or other way
 * 
 * the above system attract commercial users
 * exchange system is next step to attract non-commercial users
 * 
 * 
 * user: adjust weight of ads
 * 
 * 
 * 
    
 *    
 * 
 */

/**
 * Displays DUAL lodgment page to user
 * 
 * @package broker_sales
 * @version $Id: page_premium_lodgement.php,v 1.3 2012-10-29 00:59:54 developer Exp $
 */
 
include_once("generate_page_dual.php");
include_once("generate_page_summary.php");
include_once("sequence.php");
include_once("page_dual.php");
require_once("class.phpmailer.php");

class page_premium_lodgement extends page_dual {
   
   public function get_name()
   {
      return "Lodgement";
   }
      
   /**
   * Display the lodgement page. User can get lodgement PDF
   *
   * @param generate_page_base $generate_page  Page maker for the page
   * @param array $info Associative array holding buttons, form information and controls. $info['form'] is the form start tag, $info['controls'] is the HTML for controls
   * @param integer $policyid Policy id of the current policy
   * 
   * @return string
   * @access public
   */     
   public function generate_page(generate_page_base $generate_page, $info, $policyid) 
   {
      if (!$info['summary']) {
         
         $result=ics_query("SELECT * FROM data_client dcl, data_contact dco,  data_policy dp WHERE  dcl.data_policyid='{$policyid}' AND dco.data_clientid=dcl.data_clientid AND dp.data_policyid='{$policyid}'");
         $row=ics_fetch_array($result);
         $lobid=get_lobid_from_sid();
         if ($row)   {

            $str=$generate_page->get_popup_script();
            
            // recorded if is an invoice but confirmed if credit card
            if ($info['paylater'])
               $conf='recorded';
            else
               $conf='confirmed';
            $str.="<br/>";
            $str.="<table width='100%'><td align='center'> ";
            $str.="<div style='margin: 0 auto; width:700px; padding:20px; iborder:2px solid black; text-align:left; font-weight:bolder'>";
        
	            $str.=$generate_page->get_line("<h4 style='line-height:1.5'>Your Premium Funding Application has been emailed to you. As soon as we have received and processed the completed form we will email your Cover Confirmation.</h4><br/>"); 
                if($lobid==1){
		            $str.=$generate_page->get_line("<h4 style='line-height:1.5'>As a result of completing your insurance with Express Insurance we will make a donation to the Gutsy Group. To read more about this worthwhile charity click on the icon below.                
	                <br/><br/><p align='center'><a href='#' onclick='window.open(\"http://www.thegutsygroup.com.au/\")'/><img src='images/gutsygroup.gif'/></a></p>
	                </h4>");
                } if($lobid==3){
	                $str.=$generate_page->get_line("<h4 style='line-height:1.5'>As a result of completing your insurance with Express Insurance we will make a donation to St Vincent's Institute of Medical Research. To read more about this worthwhile charity click on the icon below.
                <br/><br/><p align='center'><a title=\"St vincent's institute\" href=\"javascript: void(0)\" onclick=\"window.open('http://www.svi.edu.au/', wqbepos_window_name,  wqbepos_appearance); return false;\"><img src=\"images/svi.gif\" alt=\"st vincent's institute\"/></a></p>
                </h4>");
                }
               $str.=$generate_page->get_line("<h4 style='line-height:1.5'>Thank you for using Express Insurance.</h4>
               		<br/><br/>To make sure that you can receive a reply from Express Insurance, add \"online_sales@expressinsurance.com.au\" contact to your email addressbook.
If you do not receive a response in your \"inbox\", please check your \"bulk mail\",\"spam\" or \"junk mail\" folders.<br/><br/><br/>");
         
            //$str.=$generate_page->get_line("<span style='font-size:15px'>We are pleased to advise that<br/><br/>your insurance cover<br/><br/>is now in place.<br/><br/> Please click below to produce your<br/><br/> certificate of insurance.<br/><br/>All other documentation will <br/><br/>be emailed to you shortly.</span>");
            //$str.="Your payment has been ".$conf.".<br/> Please click below to produce your certificate of insurance.<br/><br/>All other documentation will be emailed to you shortly.";
            //$str.=$generate_page->get_line("Your payment has been ".$conf.".<br/> Please click below to produce your certificate of insurance.<br/><br/>All other documentation will be emailed to you shortly.");
            $str.="</div></td></table><br/><br/><br/> ";
            //$str.=$generate_page->get_line("Certificate of insurance has been ".$conf.". Documentation will be sent to your email address within 3 working days.<br/><br/>");

           
           
           
            // This is not the place to be sending off the confirmation!
            $pdfinfo=$info;
            $pdfinfo['summary']=true;
            $file= ics_query_item("SELECT confirmation_pdf FROM data_policy  WHERE data_policyid='{$policyid}'");
                    
            $str.="<input type='hidden' id='pdffile' value='{$file}'/>";
            $str.='<script>
            var wqbepos_screen_height=screen.height-20;
var wqbepos_screen_width=screen.width-20;
var wqbepos_window_name="WQBE_POS";
var wqbepos_appearance = "width=" + wqbepos_screen_width + ",height=" + wqbepos_screen_height + ",top=0,left=10,resizable=yes,";
wqbepos_appearance += "location=no,scrollbars=1,status=0,menubar=0,toolbar=no,titlebar=1";
            </script>';
         }
      }
   }
}
            
