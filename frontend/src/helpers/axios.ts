import { config } from "@/../env.d";
import axios from "axios";
import NotificationHelper from "@/helpers/NotificationHelper";

const token = localStorage.getItem('bearerToken');
axios.interceptors.request.use(function (axiosConfig) {
	axiosConfig.headers = {
		Authorization: `Bearer ${token}`,
		Accept: 'application/json'
	}
	return axiosConfig;
	}, function (error) {
	return Promise.reject(error);
});

axios.interceptors.response.use(function(response) {
	return response;
}, function(error) {
	if(error?.response?.data?.errors) {
		NotificationHelper.NotifyFormValidation(error.response.data.errors);
	} else {
		NotificationHelper.NotifyError(error.response.data?.message);
	}
	if(error.response.status == 401) {
		localStorage.removeItem('bearerToken');
		localStorage.removeItem('userDetails');
		(window as Window).location = '/login';
	}
	return Promise.reject(error);
});

axios.defaults.baseURL = config.apiUrl;
axios.defaults.withCredentials = true;