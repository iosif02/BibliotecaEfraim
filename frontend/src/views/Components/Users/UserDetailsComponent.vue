<script setup lang="ts">
import type UserModel from '@/models/user/UserModel';
import 'primeicons/primeicons.css'
import ChevronLeftIcon from "@/views/Components/Icons/ChevronLeftIcon.vue";
import { ref, type PropType } from 'vue'

defineProps({
    user: {
        type: Object as PropType<UserModel>,
        required: true
    }
});

const isToggle = ref(false);
const arrowStyle = ref('rotate(90deg)');
const arrowAlign = ref('space-between');
const cardStyle = ref({ height: '35px', color: '#76CECB' })

const toggleCard = () => {
    isToggle.value = !isToggle.value;
    cardStyle.value.height = isToggle.value ? '200px' : '35px';
    cardStyle.value.color = isToggle.value ? '#F6F6F6' : '#76CECB';
    arrowStyle.value = isToggle.value ? 'rotate(270deg)' : 'rotate(90deg)';
    arrowAlign.value = isToggle.value ? 'end' : 'space-between';
};

const calculateAge = (birthdate: Date) => {
    if (birthdate) {
        const today = new Date();
        const birthDate = new Date(birthdate);
        
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();

        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        return age;
    }
}

const backgroundColor = [
    'background-color: #001219;',
    'background-color: #005F73;',
    'background-color: #0A9396;',
    'background-color: #94D2BD;',
    'background-color: #E9D8A6;',
    'background-color: #EE9B00;',
    'background-color: #CA6702;',
    'background-color: #BB3E03;',
    'background-color: #AE2012;',
    'background-color: #9B2226;',
]

function getRandomColor() {
    const randomNumber = Math.floor(Math.random() * 10)
    return backgroundColor[randomNumber]
}

function getNameIntial(first_initial: String, second_initial: String) {
    if(first_initial == second_initial) return first_initial;
    return first_initial + '' + second_initial;
}

</script>

<template>
    <div class="user-details-card" :style="{ height: cardStyle.height, backgroundColor: cardStyle.color }">
        <div class="arrow-container" :style="{ justifyContent: arrowAlign}" @click="toggleCard">
            <p v-if="!isToggle" class="text-elipsis">Email: {{ user?.user_details.phone }}</p>
            <ChevronLeftIcon class="icon" :style="{ transform: arrowStyle }" />
            <!-- <i class="pi pi-arrow-down" :style="arrowStyle"></i> -->
        </div>
        <div v-if="isToggle" class="user-details">
            <div class="top-card">
                <div v-if="user.user_details?.photo_url" class="image-container">
                    <img :src="$filters.imageFilter(user.user_details?.photo_url)" alt="">
                </div>
                <div v-else class="user-image" :style="getRandomColor()">
                        <p>{{ getNameIntial(user.first_name[0], user.last_name[0]) }}</p>
                    </div>
                <div class="contact-details">
                    <p class="text-elipsis">{{ user?.email }}</p>
                    <p class="text-elipsis">{{ user.user_details?.phone }}</p>
                </div>
            </div>
            <div class="bottom-section">
                <div class="bottom-card">
                    <p class="text-elipsis">Identity number: <span>{{ user.user_details?.identity_number }}</span></p>
                    <p class="text-elipsis">Adress: <span>{{ user.user_details?.address }}</span></p>
                    <p class="text-elipsis">Occupation: <span>{{ user.user_details?.occupation }}</span></p>
                    <p class="text-elipsis">Age: <span>{{ calculateAge(user.user_details?.birth_date) }}</span></p>
                </div>
                <button class="btn-edit" @click="$router.push({ name: 'editReader', params: { id: user.id } })">Edit</button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.user-details-card {
    width: 100%;
    padding: 10px 10px;
    margin-bottom: 15px;
    border-radius: 8px;
    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.15);
    position: relative;
    transition: 0.5s ease-in-out;
}
.arrow-container{
    display: flex;
    flex-direction: row;
}
.icon {
    transition: transform 0.5s ease-in-out;
}
.user-details {
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
    overflow: hidden;
}
.top-card {
    widows: 100%;
    display: flex;
    flex-direction: row;
    gap: 10px;
    overflow: hidden;
}
.image-container {
    height: 70px;
    min-width: 70px;
    max-width: 70px;
    border-radius: 50%;
    /* margin-right: 10px; */
}
.user-image{
    height: 70px;
    min-width: 70px;
    max-width: 70px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
}
.user-image p{
    color: #fff;
    font-size: 2rem;
    font-weight: 600;
}
.image-container img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}
.contact-details {
    display: flex;
    flex-direction: column;
    justify-content: center;
    overflow: hidden;
}
.bottom-section{
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: end;
}
.bottom-card {
    margin-top: 15px;
    padding-left: 15px;
}
p {
    font-size: 14px;
    font-family: 'Roboto-500';
    color: #4D4D4D;
    line-height: 18px;
}
span {
    color: #4D4D4D;
}
.btn-edit{
    margin: 0;
}
</style>