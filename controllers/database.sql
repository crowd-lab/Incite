--
-- Dumping data for table `omeka_incite_tag_answer_explain_list`
--

INSERT INTO `omeka_incite_tag_answer_explain_list` (`id`, `item_id`, `question_id`, `answer`, `correct`, `explanation`) VALUES 
(1, 731, 1, "1861-06-29", "true", "It is shown in Document Information window"),
(2, 731, 2, "Louisiana - Orleans Parish - New Orleans", "true", "It is shown in Document Information window"),
(3, 731, 3, "Shreveport", "true", " the document tells us about a celebration in Shreveport, Louisiana. It also tells us about attitudes in the South and the Confederacy more generally."),
(4, 731, 3, "Louisiana", "true", " the document tells us about a celebration in Shreveport, Louisiana. It also tells us about attitudes in the South and the Confederacy more generally."),
(5, 731, 3, "the Confederacy", "true", "the document tells us about a celebration in Shreveport, Louisiana. It also tells us about attitudes in the South and the Confederacy more generally."),
(6, 731, 3, "The South", "true", "the document tells us about a celebration in Shreveport, Louisiana. It also tells us about attitudes in the South and the Confederacy more generally."),
(7, 731, 3, "South", "true", "the document tells us about a celebration in Shreveport, Louisiana. It also tells us about attitudes in the South and the Confederacy more generally."),
(8, 731, 4, "Pre Civil war (year range, - 1861)", "false", "Almost- the document does tell us about how southerners remembered events that took place before the Civil War: the American Revolution and the Declaration of Independence. But it actually tells us more about events and attitudes in 1861, at the beginning of the Civil War"),
(9, 731, 4, "Civil war (year range, 1861 - 1865)", "true", "the document was published in 1861, at the beginning of the Civil War, and tells us about events and attitudes during 1861"),
(10, 731, 4, "Post Civil war (year range, 1865 - )", "false", "the document was published in 1861 and does not tell us about the post-Civil War period"),
(11, 731, 4, "Unclear", "false", ""),
(12, 731, 5, "White Americans", "true", "although the document does not specifically say that it comes from a white perspective, southern newspapers, military organizations, and public life in general excluded African Americans in 1861. So we can safely assume that this document comes from a white perspective"),
(13, 731, 5, "African Americans", "false", "although the document does not specifically say that it comes from a white perspective, southern newspapers, military organizations, and public life in general excluded African Americans in 1861. So we can safely assume that this document comes from a white perspective"),
(14, 731, 5, "Foreigners", "false", "there is no evidence in the document to suggest a foreign perspective"),
(15, 731, 5, "Not specified", "true", "the document does not directly say what racial perspective it comes from. However, since southern newspapers, military organizations, and public life in general excluded African Americans in 1861, we can safely assume that this document comes from a white perspective"),
(16, 731, 6, "Male", "true", "although the document does not specifically say that it comes from a male perspective, nineteenth-century newspapers were written and edited primarily by men. Without any evidence to the contrary, we can assume it comes from a male perspective."),
(17, 731, 6, "Female", "false", "although the document does not specifically say that it comes from a male perspective, nineteenth-century newspapers were written and edited primarily by men. Without any evidence to the contrary, we can assume it does not come from a female perspective."),
(18, 731, 6, "Not specified", "true", "the document does not directly say what gender perspective it comes from (but since most nineteenth-century newspapers were written and edited by men, it is more likely to be from a male perspective"),
(19, 731, 5, "Abolitionists", "false", "There's no evidence in the document to suggest an abolitionist perspective");

-- --------------------------------------------------------

--
-- Dumping data for table `omeka_incite_tag_question_index`
--

INSERT INTO `omeka_incite_tag_question_index` (`id`, `question`) VALUES 
(1, "date"),
(2, "place"),
(3, "referred_place"),
(4, "period"),
(5, "socail"),
(6, "gender"),
(7, "occupation");

-- --------------------------------------------------------

--
-- Dumping data for table `omeka_incite_trans_reason`
--

INSERT INTO `omeka_incite_trans_reason` (`id`, `item_id`,`reason`) VALUES 
(1, 731, "The author is expressing pride in the Confederacy and the way Confederates are honoring southerners' previous contributions to the Declaration of Independence, the American Revolution, and the Fourth of July.");


-- --------------------------------------------------------

--
-- Dumping data for table `omeka_incite_subject_explain`
--

INSERT INTO `omeka_incite_subject_explain` (`id`, `item_id`, `concept_id`, `explanation`) VALUES 
(1, 731, 1, 'The document does not refer to religion.'),
(2, 731, 2, 'Although white supremacy certainly prevailed in the South at this time, there is no reference to it in this document.'),
(3, 731, 3, 'The document does not refer to racial equality.'),
(4, 731, 4, 'Although gender inequality certainly prevailed in the United States at this time, there is no reference to it in this document.'),
(5, 731, 5, 'The document does not refer directly to human equality. But we might infer that the reference to the Declaration of Independence indicates that the author sees the Confederate cause as being somehow connected to human equality.'),
(6, 731, 6, 'We can infer that "the doctrine for which we are to-day fighting" refers to the ideal of self-government, derived from the Declaration of Independence.'),
(7, 731, 7, 'The document does not refer to any international context or meaning of July 4.'),
(8, 731, 8, 'The document enthusiastically celebrates the achievements of George Washington and other southern participants in the American Revolution.'),
(9, 731, 9, 'The document clearly reveals what white southerners thought about July 4 in 1861.');

-- --------------------------------------------------------

--
-- Dumping data for table `omeka_incite_tags`
--

INSERT INTO `omeka_incite_tags` (`id`, `item_id`, `tagged_trans_id`, `user_id`, `working_group_id`, `tag_text`, `created_timestamp`, `category_id`, `description`, `type`) VALUES 
(NULL, 731, 0, 0, 0, "SHREVEPORT", "2017-04-10 10:58:43", 1, "", 2),
(NULL, 731, 0, 0, 0, "Southwestern", "2017-05-07 21:03:10", 4, "", 2),
(NULL, 731, 0, 0, 0, "The Yankees", "2017-05-07 21:03:30", 3, "", 2),
(NULL, 731, 0, 0, 0, "Washington", "2017-05-07 21:03:53", 3, "", 2),
(NULL, 731, 0, 0, 0, "Shreveport Sentinels", "2017-05-07 21:04:17", 4, "", 2),
(NULL, 731, 0, 0, 0, "Summer Grove cavalry", "2017-05-07 19:20:07", 4, "", 2),
(NULL, 731, 0, 0, 0, "Confederacy", "2017-04-10 11:02:03", 1, "", 2),
(NULL, 731, 0, 0, 0, "Keachi company", "2017-05-07 19:24:20", 4, "", 2),
(NULL, 731, 0, 0, 0, "parade", "2017-05-08 12:10:08", 2, "", 2),
(NULL, 731, 0, 0, 0, "dinner", "2017-05-08 12:10:00", 2, "", 2);

-- --------------------------------------------------------

--
-- Dumping data for table `omeka_incite_documents_subject_conjunction`
--

INSERT INTO `omeka_incite_documents_subject_conjunction` (`id`, `item_id`, `tagged_trans_id`, `subject_concept_id`, `rank`, `user_id`, `working_group_id`, `type`, `created_time`) VALUES 
(NULL, 731, 24, 1, 1, 0, 0, 2, "2017-04-10 15:56:32"),
(NULL, 731, 24, 2, 1, 0, 0, 2, "2017-04-10 15:57:21"),
(NULL, 731, 24, 3, 1, 0, 0, 2, "2017-04-10 15:57:21"),
(NULL, 731, 24, 4, 1, 0, 0, 2, "2017-04-10 15:57:56"),
(NULL, 731, 24, 5, 2, 0, 0, 2, "2017-05-08 17:28:20"),
(NULL, 731, 24, 6, 3, 0, 0, 2, "2017-05-08 17:28:38"),
(NULL, 731, 24, 7, 1, 0, 0, 2, "2017-04-10 16:00:32"),
(NULL, 731, 24, 8, 5, 0, 0, 2, "2017-05-08 17:28:56"),
(NULL, 731, 24, 9, 5, 0, 0, 2, "2017-05-08 17:29:02");

-- --------------------------------------------------------

--
-- Dumping data for table `omeka_incite_transcriptions`
--

INSERT INTO `omeka_incite_transcriptions` (`id`, `item_id`, `user_id`, `working_group_id`, `transcribed_text`, `summarized_text`, `tone`, `type`, `timestamp_approval`, `timestamp_creation`) VALUES 
(NULL, 731, 0, 0, 'THE FOURTH OF JULY AT SHREVEPORT - We learn from the Southwestern that it is the purpose of the military companies there to celebrate the Fourth of July by a general review, grand parade and dinner. It says:The Yankees have robbed us of too much already. We have no idea of giving up the national anniversary-not a bit of it. The Fourth of July is ours. The declaration of independence declared and reiterated the doctrine for which we are to-day fighting. It was drafted by a southern man and advocated by Washington and a host of other southern heroes. The Shreveport Sentinels have appointed a committee to consult with similar committees to be appointed by the artillery company-the Summer Grove cavalry and the Keachi company, for the purpose of carrying out this laudable purpose. Long live the Confederacy, and huzza for the old Fourth of July.', '', 'pride', 2, NULL, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Dumping data for table `omeka_incite_tag_answer_explain_list`
--

INSERT INTO `omeka_incite_tag_answer_explain_list` (`id`, `item_id`, `question_id`, `answer`, `correct`, `explanation`) VALUES;

-- --------------------------------------------------------

--
-- Dumping data for table `omeka_incite_tag_question_index`
--

INSERT INTO `omeka_incite_tag_question_index` (`id`, `question`) VALUES;

-- --------------------------------------------------------

--
-- Dumping data for table `omeka_incite_trans_reason`
--

INSERT INTO `omeka_incite_trans_reason` (`id`, `item_id`,`reason`) VALUES 


-- --------------------------------------------------------

--
-- Dumping data for table `omeka_incite_subject_explain`
--

INSERT INTO `omeka_incite_subject_explain` (`id`, `item_id`, `concept_id`, `explanation`) VALUES;

-- --------------------------------------------------------

--
-- Dumping data for table `omeka_incite_tags`
--

INSERT INTO `omeka_incite_tags` (`id`, `item_id`, `tagged_trans_id`, `user_id`, `working_group_id`, `tag_text`, `created_timestamp`, `category_id`, `description`, `type`) VALUES;

-- --------------------------------------------------------

--
-- Dumping data for table `omeka_incite_documents_subject_conjunction`
--

INSERT INTO `omeka_incite_documents_subject_conjunction` (`id`, `item_id`, `tagged_trans_id`, `subject_concept_id`, `rank`, `user_id`, `working_group_id`, `type`, `created_time`) VALUES;

-- --------------------------------------------------------

--
-- Dumping data for table `omeka_incite_transcriptions`
--

INSERT INTO `omeka_incite_transcriptions` (`id`, `item_id`, `user_id`, `working_group_id`, `transcribed_text`, `summarized_text`, `tone`, `type`, `timestamp_approval`, `timestamp_creation`) VALUES;

-- --------------------------------------------------------

