export default class UserDetailsModel {
	user_id: number = 0
    identity_number: number = 0
    first_name: string = "";
    last_name: string = "";
    address: string = "";
    phone: number = 0;
    occupation: string = "";
    birth_date: Date = new Date();
    photo_url: string = "";
}