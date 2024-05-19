<script setup lang="ts">
import { RouterLink } from "vue-router";
import { Form, Field, ErrorMessage } from 'vee-validate';
import { authStore } from '@/stores/auth-store';
import * as yup from 'yup';
import { ref } from "vue";

defineProps({
	userCreated: {
		type: Boolean,
		default: false
	}
});

let isLoading = ref<boolean>(false);

const store = authStore();
async function login(model: any): Promise<void> {
	isLoading.value = true;
	let result = await store.login(model);
	if(result) {
		isLoading.value = false;
		location.reload();
	}
	isLoading.value = false;
}

const validateForm = yup.object({
	email: yup.string().required().email(),
	password: yup.string().required().min(8),
});

</script>

<template>
	<h2 class="auth-title">Login</h2>
	<Form class="form-control" @submit="login" :validation-schema="validateForm">
		<div class="form-group">
			<label>Email <span class="mandatory">*</span> </label>
			<Field name="email" type="email" />
			<ErrorMessage name="email" />
		</div>
		<div class="form-group">
			<label>Password <span class="mandatory">*</span> </label>
			<Field name="password" type="password" />
			<ErrorMessage name="password" />
		</div>
		<div class="btn-container">
			<input :value="isLoading ? '' : 'Sign In'" type="submit" class="btn w-100 m-0">
			<LoadingButton v-if="isLoading" />
		</div>
		<!-- <p class="small-text">
			You don't have an account?
			<RouterLink :to="{ name: 'register' }">Register here</RouterLink>
		</p> -->
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