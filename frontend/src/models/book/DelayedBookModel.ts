import BookModel from "@/models/book/BookModel";
import UserModel from "@/models/user/UserModel";

export default class DelayedBookModel {
	borrow_date: string = "";
    is_returned: boolean = false;
    return_date: string = "";

    book: BookModel = new BookModel();
    user: UserModel = new UserModel();
}