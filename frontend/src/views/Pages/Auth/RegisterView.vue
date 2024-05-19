<script setup lang="ts">
import { Form, Field, ErrorMessage } from 'vee-validate';
import { authStore } from '@/stores/auth-store';
import { useRouter } from "vue-router";
import * as yup from 'yup';
import { ref } from 'vue';

const router = useRouter();
const store = authStore();
let isLoading = ref<boolean>(false);

async function register(model: any): Promise<void> {
	isLoading.value = true;
	let result = await store.register(model);
	if(result){
		isLoading.value = false;
		router.replace({ name: "login" });
	}
	isLoading.value = false;
}

const validateForm = yup.object({
	first_name: yup.string().required(),
	last_name: yup.string().required(),
	email: yup.string().required().email(),
	password: yup.string().required().min(8),
	password_confirmation: yup.string().required().oneOf([yup.ref('password')], 'Passwords do not match')
});
</script>

<template>
	<h2 class="auth-title">Create Account</h2>
	<Form class="form-control" @submit="register" :validation-schema="validateForm">
		<div class="form-group">
			<p>First Name <span class="mandatory">*</span> </p>
			<Field name="first_name" type="text" />
			<ErrorMessage name="email" />
		</div>
		<div class="form-group">
			<p>Last Name <span class="mandatory">*</span> </p>
			<Field name="last_name" type="text" />
			<ErrorMessage name="email" />
		</div>
		<div class="form-group">
			<p>Email <span class="mandatory">*</span> </p>
			<Field name="email" type="email" />
			<ErrorMessage name="email" />
		</div>
		<div class="form-group">
			<p>Password <span class="mandatory">*</span> </p>
			<Field name="password" type="password" />
			<ErrorMessage name="password" />
		</div>
		<div class="form-group">
			<p>Confirm Password <span class="mandatory">*</span> </p>
			<Field name="password_confirmation" type="password" />
			<ErrorMessage name="password_confirmation" />
		</div>
		<div class="btn-container">
			<input type="submit" :value="isLoading ? '' : 'Register'" class="btn w-100 m-0">
			<LoadingButton v-if="isLoading" />
		</div>
		<p class="small-text">
			Already have an account?
			<RouterLink :to="{ name: 'login' }">Login here</RouterLink>
		</p>
	</Form>
</template>

<style scoped>
	.small-text {
		font-size: .75rem;
		text-align: center;
	}
	.btn-container {
		position: relative;
		margin: 1rem 0rem .75rem 0rem;
		height: 54px;
	}
</style>