import BookModel from "@/models/book/BookModel";
import UserModel from "@/models/user/UserModel";

export default class DelayedBookModel {
    id: number = 0;
	borrow_date: string = "";
    is_returned: boolean = false;
    return_date: string = "";
    lender_name: string = "";
    receiver_name: string = "";
    delayed: number = 0;

    book: BookModel = new BookModel();
    user: UserModel = new UserModel();
}