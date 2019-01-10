# Multiple-Step-Forms
Multiple Step Forms


--
-- Table structure for table `forms`
-- This will store all forms.
--
CREATE TABLE `forms` (
  `form_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_date` datetime NOT NULL,
  `modify_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `forms` (`form_id`, `user_id`, `name`, `created_date`, `modify_date`) VALUES
(1, 1, 'A Form', '2019-01-04 10:28:04', NULL),
(2, 1, 'B Form', '2019-01-04 10:28:04', '2019-01-04 10:28:04'),
(3, 1, 'C Form', '2019-01-04 10:28:04', NULL),
(4, 1, 'D Form', '2019-01-04 10:28:04', NULL);

--
-- Table structure for table `form_fields`
-- This will store all step in a forms.
--  Foreign Key Form ID
--
CREATE TABLE `form_fields` (
  `field_id` int(11) NOT NULL,
  `form_heading` varchar(100) NOT NULL,
  `form_id` int(11) NOT NULL DEFAULT '0',
  `step` varchar(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `form_fields` (`field_id`, `form_heading`, `form_id`, `step`) VALUES
(1, 'Contact Details', 2, 'step1'),
(2, 'Proposed company Information', 2, 'step2'),
(3, 'Registered Office Details', 2, 'step3'),
(4, 'Share Capital', 2, 'step4'),
(5, 'Directors Cum Shareholder Details', 2, 'step5'),
(6, 'Details to be filled by Truestartup', 2, 'step6');

--
-- Table structure for table `post_values`
--  post_values column will store all data as serialize in every steps.
--

CREATE TABLE `post_values` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `form_process_id` varchar(20) NOT NULL,
  `form_id` int(11) NOT NULL DEFAULT '0',
  `field_id` int(11) NOT NULL,
  `post_values` longtext NOT NULL,
  `created_date` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `post_documents`
--  if you have any file browse in any step form you can use below table.
--

CREATE TABLE `post_documents` (
  `document_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `doc_key` varchar(50) NOT NULL,
  `file_type` varchar(80) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_source_name` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

