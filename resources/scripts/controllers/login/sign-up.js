var sign_up = new Vue({
	el: "#sign-up-form",
	data: {
		successMessage: "",
		errorMessage: "",
		logDetails: {first_name: "", last_name: "", photo: "", email: "", password: "", confirm_password: ""},
	},
	methods: {
		sign_up: function () {
			axios.post("login/sign-up.php", sign_up.toFormData(sign_up.logDetails))
				.then(function (response) {
					if (response.data.error) {
						sign_up.clearMessage();
						if (login !== undefined) {
							login.clearMessage();
						}
						sign_up.errorMessage = response.data.message;
					} else {
						sign_up.clearMessage();
						if (login !== undefined) {
							login.clearMessage();
						}
						sign_up.successMessage = response.data.message;
						sign_up.logDetails = {
							first_name: "",
							last_name: "",
							photo: "",
							email: "",
							password: "",
							confirm_password: ""
						};
					}
				});
		},
		toFormData: function (obj) {
			var form_data = new FormData();
			for (var key in obj) {
				form_data.append(key, obj[key]);
			}
			return form_data;
		},
		clearMessage: function () {
			sign_up.errorMessage = "";
			sign_up.successMessage = "";
		}
	}
});