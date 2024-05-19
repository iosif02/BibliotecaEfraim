import Pagination from "@/models/global/PaginationModel";

export default class SearchBookModel {
	title: string = "";
    author: number = 0;
    publisher: number = 0;
    category: number = 0;
    user: number = 0;
    isReturned: boolean = false;

    pagination: Pagination = new Pagination();
}