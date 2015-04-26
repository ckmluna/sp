--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: postgres; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON DATABASE postgres IS 'default administrative connection database';


--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- Name: soundex(text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION soundex(input text) RETURNS text
    LANGUAGE plpgsql IMMUTABLE STRICT COST 500
    AS $$
DECLARE
  soundex text = '';
  char text;
  symbol text;
  last_symbol text = '';
  pos int = 1;
BEGIN
  WHILE length(soundex) < 4 LOOP
    char = upper(substr(input, pos, 1));
    pos = pos + 1;
    CASE char
    WHEN '' THEN
      -- End of input string
      IF soundex = '' THEN
        RETURN '';
      ELSE
        RETURN rpad(soundex, 4, '0');
      END IF;
    WHEN 'B', 'F', 'P', 'V' THEN
      symbol = '1';
    WHEN 'C', 'G', 'J', 'K', 'Q', 'S', 'X', 'Z' THEN
      symbol = '2';
    WHEN 'D', 'T' THEN
      symbol = '3';
    WHEN 'L' THEN
      symbol = '4';
    WHEN 'M', 'N' THEN
      symbol = '5';
    WHEN 'R' THEN
      symbol = '6';
    ELSE
      -- Not a consonant; no output, but next similar consonant will be re-recorded
      symbol = '';
    END CASE;

    IF soundex = '' THEN
      -- First character; only accept strictly English ASCII characters
      IF char ~>=~ 'A' AND char ~<=~ 'Z' THEN
        soundex = char;
        last_symbol = symbol;
      END IF;
    ELSIF last_symbol != symbol THEN
      soundex = soundex || symbol;
      last_symbol = symbol;
    END IF;
  END LOOP;

  RETURN soundex;
END;
$$;


ALTER FUNCTION public.soundex(input text) OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: college; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE college (
    id_no integer NOT NULL,
    name character varying
);


ALTER TABLE public.college OWNER TO postgres;

--
-- Name: college_id_no_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE college_id_no_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.college_id_no_seq OWNER TO postgres;

--
-- Name: college_id_no_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE college_id_no_seq OWNED BY college.id_no;


--
-- Name: course; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE course (
    course_id integer NOT NULL,
    course_code character varying NOT NULL,
    units integer NOT NULL,
    course_description character varying NOT NULL,
    sem character varying NOT NULL,
    course_title character varying NOT NULL,
    prerequisites character varying
);


ALTER TABLE public.course OWNER TO postgres;

--
-- Name: course_course_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE course_course_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.course_course_id_seq OWNER TO postgres;

--
-- Name: course_course_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE course_course_id_seq OWNED BY course.course_id;


--
-- Name: course_offering; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE course_offering (
    id_no integer NOT NULL,
    semester character varying NOT NULL,
    academic_year character varying NOT NULL,
    dept_id integer NOT NULL
);


ALTER TABLE public.course_offering OWNER TO postgres;

--
-- Name: course_offering_dept_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE course_offering_dept_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.course_offering_dept_id_seq OWNER TO postgres;

--
-- Name: course_offering_dept_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE course_offering_dept_id_seq OWNED BY course_offering.dept_id;


--
-- Name: course_offering_id_no_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE course_offering_id_no_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.course_offering_id_no_seq OWNER TO postgres;

--
-- Name: course_offering_id_no_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE course_offering_id_no_seq OWNED BY course_offering.id_no;


--
-- Name: department; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE department (
    id_no integer NOT NULL,
    name character varying NOT NULL,
    college_id integer NOT NULL
);


ALTER TABLE public.department OWNER TO postgres;

--
-- Name: department_college_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE department_college_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.department_college_id_seq OWNER TO postgres;

--
-- Name: department_college_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE department_college_id_seq OWNED BY department.college_id;


--
-- Name: department_id_no_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE department_id_no_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.department_id_no_seq OWNER TO postgres;

--
-- Name: department_id_no_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE department_id_no_seq OWNED BY department.id_no;


--
-- Name: faculty; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE faculty (
    id_no integer NOT NULL,
    first_name character varying NOT NULL,
    middle_name character varying NOT NULL,
    last_name character varying NOT NULL,
    rank character varying NOT NULL,
    dept_id integer NOT NULL,
    username character varying NOT NULL,
    password character varying NOT NULL,
    emp_no character varying NOT NULL,
    status character varying
);


ALTER TABLE public.faculty OWNER TO postgres;

--
-- Name: faculty_dept_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE faculty_dept_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.faculty_dept_id_seq OWNER TO postgres;

--
-- Name: faculty_dept_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE faculty_dept_id_seq OWNED BY faculty.dept_id;


--
-- Name: faculty_id_no_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE faculty_id_no_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.faculty_id_no_seq OWNER TO postgres;

--
-- Name: faculty_id_no_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE faculty_id_no_seq OWNED BY faculty.id_no;


--
-- Name: lab_section; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE lab_section (
    id_no integer NOT NULL,
    section_no character varying NOT NULL,
    "time" character varying NOT NULL,
    day character varying NOT NULL,
    room character varying NOT NULL,
    class_size integer NOT NULL,
    faculty_id integer NOT NULL,
    lecture_id integer NOT NULL
);


ALTER TABLE public.lab_section OWNER TO postgres;

--
-- Name: lab_section_faculty_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE lab_section_faculty_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.lab_section_faculty_id_seq OWNER TO postgres;

--
-- Name: lab_section_faculty_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE lab_section_faculty_id_seq OWNED BY lab_section.faculty_id;


--
-- Name: lab_section_id_no_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE lab_section_id_no_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.lab_section_id_no_seq OWNER TO postgres;

--
-- Name: lab_section_id_no_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE lab_section_id_no_seq OWNED BY lab_section.id_no;


--
-- Name: lab_section_lecture_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE lab_section_lecture_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.lab_section_lecture_id_seq OWNER TO postgres;

--
-- Name: lab_section_lecture_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE lab_section_lecture_id_seq OWNED BY lab_section.lecture_id;


--
-- Name: lec_section; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE lec_section (
    id_no integer NOT NULL,
    section_name character varying NOT NULL,
    course_id integer NOT NULL,
    "time" character varying NOT NULL,
    faculty_id integer NOT NULL,
    class_size integer,
    day character varying NOT NULL,
    room character varying NOT NULL,
    co_id integer NOT NULL
);


ALTER TABLE public.lec_section OWNER TO postgres;

--
-- Name: lecture_section_co_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE lecture_section_co_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.lecture_section_co_id_seq OWNER TO postgres;

--
-- Name: lecture_section_co_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE lecture_section_co_id_seq OWNED BY lec_section.co_id;


--
-- Name: ovci; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE ovci (
    id_no integer NOT NULL,
    first_name character varying NOT NULL,
    middle_name character varying NOT NULL,
    last_name character varying NOT NULL,
    username character varying NOT NULL,
    password character varying NOT NULL
);


ALTER TABLE public.ovci OWNER TO postgres;

--
-- Name: ovci_id_no_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ovci_id_no_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ovci_id_no_seq OWNER TO postgres;

--
-- Name: ovci_id_no_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE ovci_id_no_seq OWNED BY ovci.id_no;


--
-- Name: section_course_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE section_course_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.section_course_id_seq OWNER TO postgres;

--
-- Name: section_course_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE section_course_id_seq OWNED BY lec_section.course_id;


--
-- Name: section_faculty_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE section_faculty_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.section_faculty_id_seq OWNER TO postgres;

--
-- Name: section_faculty_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE section_faculty_id_seq OWNED BY lec_section.faculty_id;


--
-- Name: section_section_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE section_section_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.section_section_id_seq OWNER TO postgres;

--
-- Name: section_section_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE section_section_id_seq OWNED BY lec_section.id_no;


--
-- Name: student; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE student (
    id_no integer NOT NULL,
    student_no character varying(10) NOT NULL,
    first_name character varying NOT NULL,
    middle_name character varying NOT NULL,
    last_name character varying NOT NULL,
    degree character varying NOT NULL,
    college_id integer NOT NULL
);


ALTER TABLE public.student OWNER TO postgres;

--
-- Name: student_college_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE student_college_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.student_college_id_seq OWNER TO postgres;

--
-- Name: student_college_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE student_college_id_seq OWNED BY student.college_id;


--
-- Name: student_id_no_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE student_id_no_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.student_id_no_seq OWNER TO postgres;

--
-- Name: student_id_no_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE student_id_no_seq OWNED BY student.id_no;


--
-- Name: id_no; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY college ALTER COLUMN id_no SET DEFAULT nextval('college_id_no_seq'::regclass);


--
-- Name: course_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY course ALTER COLUMN course_id SET DEFAULT nextval('course_course_id_seq'::regclass);


--
-- Name: id_no; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY course_offering ALTER COLUMN id_no SET DEFAULT nextval('course_offering_id_no_seq'::regclass);


--
-- Name: id_no; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY department ALTER COLUMN id_no SET DEFAULT nextval('department_id_no_seq'::regclass);


--
-- Name: id_no; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY faculty ALTER COLUMN id_no SET DEFAULT nextval('faculty_id_no_seq'::regclass);


--
-- Name: id_no; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY lab_section ALTER COLUMN id_no SET DEFAULT nextval('lab_section_id_no_seq'::regclass);


--
-- Name: id_no; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY lec_section ALTER COLUMN id_no SET DEFAULT nextval('section_section_id_seq'::regclass);


--
-- Name: id_no; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ovci ALTER COLUMN id_no SET DEFAULT nextval('ovci_id_no_seq'::regclass);


--
-- Name: id_no; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY student ALTER COLUMN id_no SET DEFAULT nextval('student_id_no_seq'::regclass);


--
-- Data for Name: college; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO college VALUES (10, 'College of Agriculture');
INSERT INTO college VALUES (11, 'College of Arts and Sciences');
INSERT INTO college VALUES (12, 'College of Development Communication');
INSERT INTO college VALUES (13, 'College of Engineering and Agro-Industrial Technology');
INSERT INTO college VALUES (14, 'College of Economics and Management');
INSERT INTO college VALUES (15, 'College of Forestry and Natural Resources');
INSERT INTO college VALUES (16, 'College of Human Ecology');
INSERT INTO college VALUES (17, 'College of Public Affairs and Development');
INSERT INTO college VALUES (18, 'College of Veterinary Medicine');
INSERT INTO college VALUES (19, 'Graduate School');
INSERT INTO college VALUES (20, 'School of Environmental Science and Management');


--
-- Name: college_id_no_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('college_id_no_seq', 20, true);


--
-- Data for Name: course; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO course VALUES (33, 'CMSC 11', 3, 'Introduction to the major areas of computer science; software systems and methodology; computer theory; computer organization and architecture.
', '1,2,S
', 'Introduction to Computer Science', 'MATH 11 or MATH 17
');
INSERT INTO course VALUES (34, 'CMSC 21', 3, 'Expansion and development of materials introduced in CMSC 11; processing files and linked-lists; programming in the C language; recursion; systematic program development; top-down design and program verification.
', '1,2
', 'Fundamentals of Programming', 'CMSC 11
');
INSERT INTO course VALUES (35, 'CMSC 22', 3, 'Programming using an object-oriented language.
', '1,2
', 'Object-Oriented Programming', 'CMSC 11
');
INSERT INTO course VALUES (36, 'CMSC 56', 3, 'Principles of logic, set theory, relations and functions, Boolean algebra and algebra.
', '2
', 'Discrete Mathematical Structures in Computer Science I', 'MATH 17
');
INSERT INTO course VALUES (37, 'CMSC 57', 3, 'Principles of combinatorics, probability, algebraic systems and graph theory.
', '1
', 'Discrete Mathematical Structures in Computer Science II', 'CMSC 56');
INSERT INTO course VALUES (32, 'CMSC 2', 3, 'Introduction to the Internet.
', '1,2', 'Introduction to the Internet', 'COI');


--
-- Name: course_course_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('course_course_id_seq', 37, true);


--
-- Data for Name: course_offering; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO course_offering VALUES (34, '2nd Semester', '2013-2014', 17);


--
-- Name: course_offering_dept_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('course_offering_dept_id_seq', 1, false);


--
-- Name: course_offering_id_no_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('course_offering_id_no_seq', 34, true);


--
-- Data for Name: department; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO department VALUES (9, 'Agricultural Systems Cluster', 10);
INSERT INTO department VALUES (10, 'Animal and Dairy Sciences Cluster', 10);
INSERT INTO department VALUES (11, 'Crop Science Cluster', 10);
INSERT INTO department VALUES (12, 'Crop Protection Cluster', 10);
INSERT INTO department VALUES (13, 'Food Science Cluster', 10);
INSERT INTO department VALUES (14, 'Central Experiment Station', 10);
INSERT INTO department VALUES (15, 'Institute of Biological Sciences', 11);
INSERT INTO department VALUES (16, 'Institute of Chemistry', 11);
INSERT INTO department VALUES (17, 'Institute of Computer Science', 11);
INSERT INTO department VALUES (18, 'Institute of Mathematical Sciences and Physics', 11);
INSERT INTO department VALUES (19, 'Department of Humanities', 11);
INSERT INTO department VALUES (20, 'Institute of Statistics', 11);
INSERT INTO department VALUES (22, 'Department of Social Sciences', 11);
INSERT INTO department VALUES (23, 'Department of Human Kinetics', 11);


--
-- Name: department_college_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('department_college_id_seq', 1, true);


--
-- Name: department_id_no_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('department_id_no_seq', 23, true);


--
-- Data for Name: faculty; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO faculty VALUES (121, 'Celeste', 'Macatangay', 'Lunacel', 'Assistant Professor 1', 13, 'cmluna', 'password', '200865302', 'Active');
INSERT INTO faculty VALUES (129, 'Robert', 'Estermont', 'Baratheon', 'Assistant Professor 2', 9, 'rebaratheon', 'password', '200865305', 'On-leave');
INSERT INTO faculty VALUES (130, 'Stannis', 'Estermont', 'Baratheon', 'Assistant Professor 1', 10, 'sebaratheon', 'password', '200865306', 'Active');
INSERT INTO faculty VALUES (131, 'Renly', 'Estermont', 'Baratheon', 'Instructor 2', 11, 'rnlebaratheon', 'password', '200865307', 'On-leave');
INSERT INTO faculty VALUES (132, 'Jon', 'Stark', 'Snow', 'Instructor 1', 10, 'jssnow', 'password', '200865308', 'Active');
INSERT INTO faculty VALUES (133, 'Ygritte', 'The', 'Wildling', 'Instructor 1', 9, 'ytwildling', 'password', '200865309', 'Active');
INSERT INTO faculty VALUES (119, 'Celeste Kristina Romeo', 'Macatangay', 'Luna', 'Instructor 1', 10, 'ckmluna', 'password', '200865300', 'Active');
INSERT INTO faculty VALUES (123, 'Kathrine Faye', 'Ordiz', 'Tandog', 'Professor 12', 13, 'kfotandog', 'password', '200865304', 'Active');
INSERT INTO faculty VALUES (136, 'Renly', 'Estermont', 'Baratheon', 'Assistant Professor 2', 11, 'rnlebaratheon2', 'password', '200865312', 'On-leave');
INSERT INTO faculty VALUES (134, 'Robert', 'Estermont', 'Baratheon', 'Assistant Professor 2', 11, 'rebaratheon2', 'password', '200865310', 'On-leave');
INSERT INTO faculty VALUES (138, 'Ygritte', 'The', 'Wildling', 'Professor 12', 14, 'ytwildling2', 'password', '200865314', 'On-leave');
INSERT INTO faculty VALUES (120, 'Romeo', 'Felix', 'Luna', 'Instructor 2', 12, 'rfluna', 'password', '200865301', 'On-leave');
INSERT INTO faculty VALUES (137, 'Jon', 'Stark', 'Targaryen', 'Associate Professor 2', 16, 'jstargaryen', 'password', '200865313', 'Active');
INSERT INTO faculty VALUES (135, 'Stannis', 'Estermont', 'Baratheon', 'Associate Professor 1', 13, 'ssbaratheon2', 'password', '200865311', 'Active');
INSERT INTO faculty VALUES (122, 'Kristina', 'Luna', 'Zapanta', 'Associate Professor 2', 14, 'klzapanta', 'password', '200865303', 'On-leave');


--
-- Name: faculty_dept_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('faculty_dept_id_seq', 1, false);


--
-- Name: faculty_id_no_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('faculty_id_no_seq', 138, true);


--
-- Data for Name: lab_section; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO lab_section VALUES (96, '1L', '10-1', 'Tue', 'ICS PC 1', 20, 119, 34);
INSERT INTO lab_section VALUES (97, '2L', '4-7', 'Tue', 'ICS PC 2', 20, 119, 34);
INSERT INTO lab_section VALUES (98, '3L', '10-1', 'Wed', 'ICS PC 3', 20, 119, 34);
INSERT INTO lab_section VALUES (99, '4L', '1-4', 'Wed', 'ICS PC 4', 20, 119, 34);
INSERT INTO lab_section VALUES (100, '5L', '4-7', 'Wed', 'ICS PC 5', 20, 119, 34);
INSERT INTO lab_section VALUES (101, '1L', '10-1', 'Tue', 'ICS PC 1', 20, 119, 35);
INSERT INTO lab_section VALUES (102, '2L', '4-7', 'Tue', 'ICS PC 2', 20, 122, 35);
INSERT INTO lab_section VALUES (103, '3L', '10-1', 'Wed', 'ICS PC 3', 20, 121, 35);
INSERT INTO lab_section VALUES (104, '4L', '1-4', 'Wed', 'ICS PC 4', 20, 120, 35);
INSERT INTO lab_section VALUES (105, '5L', '4-7', 'Wed', 'ICS PC 5', 20, 119, 35);
INSERT INTO lab_section VALUES (106, '1L', '10-1', 'Tue', 'ICS PC 1', 20, 120, 36);
INSERT INTO lab_section VALUES (107, '2L', '4-7', 'Tue', 'ICS PC 2', 20, 122, 36);
INSERT INTO lab_section VALUES (108, '3L', '10-1', 'Wed', 'ICS PC 3', 20, 121, 36);
INSERT INTO lab_section VALUES (109, '4L', '1-4', 'Wed', 'ICS PC 4', 20, 122, 36);
INSERT INTO lab_section VALUES (110, '5L', '4-7', 'Wed', 'ICS PC 5', 20, 120, 36);


--
-- Name: lab_section_faculty_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('lab_section_faculty_id_seq', 1, false);


--
-- Name: lab_section_id_no_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('lab_section_id_no_seq', 110, true);


--
-- Name: lab_section_lecture_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('lab_section_lecture_id_seq', 1, false);


--
-- Data for Name: lec_section; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO lec_section VALUES (34, 'A', 32, '1-2', 119, 100, 'TTh', 'ICS LH 1', 34);
INSERT INTO lec_section VALUES (36, 'C', 32, '1-2', 123, 100, 'TTh', 'ICS LH 1', 34);
INSERT INTO lec_section VALUES (35, 'B', 32, '1-2', 119, 100, 'TTh', 'ICS LH 1', 34);


--
-- Name: lecture_section_co_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('lecture_section_co_id_seq', 3, true);


--
-- Data for Name: ovci; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ovci VALUES (4, 'CK-Rom', 'Macatangay', 'Luna', 'ckmluna', 'password');


--
-- Name: ovci_id_no_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ovci_id_no_seq', 4, true);


--
-- Name: section_course_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('section_course_id_seq', 1, false);


--
-- Name: section_faculty_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('section_faculty_id_seq', 1, false);


--
-- Name: section_section_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('section_section_id_seq', 36, true);


--
-- Data for Name: student; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO student VALUES (5, '200865300', 'CK-Rom', 'Macatangay', 'Luna', 'BSCS', 11);
INSERT INTO student VALUES (6, '200865301', 'Romeo', 'Felix', 'Luna', 'BSMT', 10);
INSERT INTO student VALUES (7, '200865302', 'Celeste', 'Macatangay', 'Luna', 'BSC', 11);
INSERT INTO student VALUES (8, '200865303', 'Kristina', 'Luna', 'Zapanta', 'BST', 14);
INSERT INTO student VALUES (9, '200865304', 'Kathrine Faye', 'Ordiz', 'Tandog', 'BSIE', 13);


--
-- Name: student_college_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('student_college_id_seq', 1, false);


--
-- Name: student_id_no_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('student_id_no_seq', 9, true);


--
-- Name: college_name_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY college
    ADD CONSTRAINT college_name_key UNIQUE (name);


--
-- Name: college_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY college
    ADD CONSTRAINT college_pkey PRIMARY KEY (id_no);


--
-- Name: course_course_code_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY course
    ADD CONSTRAINT course_course_code_key UNIQUE (course_code);


--
-- Name: course_offering_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY course_offering
    ADD CONSTRAINT course_offering_pkey PRIMARY KEY (id_no);


--
-- Name: course_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY course
    ADD CONSTRAINT course_pkey PRIMARY KEY (course_id);


--
-- Name: department_name_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY department
    ADD CONSTRAINT department_name_key UNIQUE (name);


--
-- Name: department_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY department
    ADD CONSTRAINT department_pkey PRIMARY KEY (id_no);


--
-- Name: faculty_emp_no_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY faculty
    ADD CONSTRAINT faculty_emp_no_key UNIQUE (emp_no);


--
-- Name: faculty_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY faculty
    ADD CONSTRAINT faculty_pkey PRIMARY KEY (id_no);


--
-- Name: faculty_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY faculty
    ADD CONSTRAINT faculty_username_key UNIQUE (username);


--
-- Name: lab_section_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY lab_section
    ADD CONSTRAINT lab_section_pkey PRIMARY KEY (id_no);


--
-- Name: ovci_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY ovci
    ADD CONSTRAINT ovci_pkey PRIMARY KEY (id_no);


--
-- Name: ovci_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY ovci
    ADD CONSTRAINT ovci_username_key UNIQUE (username);


--
-- Name: section_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY lec_section
    ADD CONSTRAINT section_pkey PRIMARY KEY (id_no);


--
-- Name: student_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY student
    ADD CONSTRAINT student_pkey PRIMARY KEY (id_no);


--
-- Name: student_student_no_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY student
    ADD CONSTRAINT student_student_no_key UNIQUE (student_no);


--
-- Name: course_offering_dept_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY course_offering
    ADD CONSTRAINT course_offering_dept_id_fkey FOREIGN KEY (dept_id) REFERENCES department(id_no) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: department_college_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY department
    ADD CONSTRAINT department_college_id_fkey FOREIGN KEY (college_id) REFERENCES college(id_no) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: faculty_dept_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY faculty
    ADD CONSTRAINT faculty_dept_id_fkey FOREIGN KEY (dept_id) REFERENCES department(id_no) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: lab_section_faculty_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY lab_section
    ADD CONSTRAINT lab_section_faculty_id_fkey FOREIGN KEY (faculty_id) REFERENCES faculty(id_no) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: lab_section_lecture_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY lab_section
    ADD CONSTRAINT lab_section_lecture_id_fkey FOREIGN KEY (lecture_id) REFERENCES lec_section(id_no) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: lec_section_co_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY lec_section
    ADD CONSTRAINT lec_section_co_id_fkey FOREIGN KEY (co_id) REFERENCES course_offering(id_no) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: lec_section_faculty_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY lec_section
    ADD CONSTRAINT lec_section_faculty_id_fkey FOREIGN KEY (faculty_id) REFERENCES faculty(id_no) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: section_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY lec_section
    ADD CONSTRAINT section_course_id_fkey FOREIGN KEY (course_id) REFERENCES course(course_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: student_college_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY student
    ADD CONSTRAINT student_college_id_fkey FOREIGN KEY (college_id) REFERENCES college(id_no) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

