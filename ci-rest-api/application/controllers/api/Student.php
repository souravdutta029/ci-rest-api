<?php
	require APPPATH.'libraries/Rest_Controller.php';

	class Student extends REST_Controller {

		/*
			Insert: Post Request Type
			Update: Put Request Type
			Delete: Delete Request Type
			Read: Get Request Type
		*/

		public function __construct()
		{
			parent::__construct();
			
			// load database
			$this->load->database();
			$this->load->model(array("api/student_model"));
			$this->load->library(array("form_validation"));
			$this->load->helper("security");
		}

		public function index_post(){
			// insert data method

			//print_r($this->input->post());
			//die;

			// Collecting form data inputs
			$name = $this->security->xss_clean($this->input->post("name"));
			$email = $this->security->xss_clean($this->input->post("email"));
			$mobile = $this->security->xss_clean($this->input->post("mobile"));
			$course = $this->security->xss_clean($this->input->post("course"));

			// form validation for inputs
			$this->form_validation->set_rules("name", "Name", "required");
			$this->form_validation->set_rules("email", "Email", "required|valid_email");
			$this->form_validation->set_rules("mobile", "Mobile", "required");
			$this->form_validation->set_rules("course", "Course", "required");

			// Checking form submission validation passed or not
			if($this->form_validation->run() === false){
				// Some errors found
				$this->response(array(
					"status" => 0,
					"message" => "All fields are needed",
				), REST_Controller::HTTP_NOT_FOUND);
			}else{
				if(!empty($name) && !empty($email) && !empty($mobile) && !empty($course)){
				// all values are available
				$student = array(
					"name" => $name,
					"email" => $email,
					"mobile" => $mobile,
					"course" => $course
				);
				if($this->student_model->insert_student($student)){
					$this->response(array(
						"status" => 1,
						"message" => "Student has been created",
					), REST_Controller::HTTP_OK);
				}else{
					$this->response(array(
						"status" => 0,
						"message" => "Failed to create student",
					), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
				}
			}else {
				// we have some empty fields
				$this->response(array(
					"status" => 0,
					"message" => "All Fields are needed",
				), REST_Controller::HTTP_NOT_FOUND);
			}
			}

			/*$data = json_decode(file_get_contents("php://input"));
			$name = isset($data->name) ? $data->name : "";
			$email = isset($data->email) ? $data->email : "";
			$mobile = isset($data->mobile) ? $data->mobile : "";
			$course = isset($data->course) ? $data->course : "";*/

			

			//echo "This is POST Method";
		}

		public function index_put(){
			// updataing data
			echo "This is PUT Method";
		}

		public function index_delete(){
			// delete data
			echo "This is DELETE Method";
		}

		public function index_get(){
			// read all data
			//echo "This is GET Method";

			$students = $this->student_model->get_students();

			//print_r($query->result());
			if(count($students) > 0){
				$this->response(array(
				"status" => 1,
				"message" => "Student Found",
				"data" => $students
			), REST_Controller::HTTP_OK);
			}else {
				$this->response(array(
				"status" => 0,
				"message" => "No Student Found",
				"data" => $students
			), REST_Controller::HTTP_NOT_FOUND);
			}
			
		}

	}
?>
