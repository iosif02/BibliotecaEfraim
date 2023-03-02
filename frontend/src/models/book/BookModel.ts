import CategoryModel from "@/models/book/CategoryModel";
import type AuthorModel from "@/models/entities/AuthorModel";
import PublisherModel from "../entities/PublisherModel";
import type TransactionModel from "./TransactionModel";

export default class BookModel {
    id: number = 0;
	title: string = "";
	year: number = 0;
    price: number = 0;
    image: string = "";
    quantity: number = 0;
    is_recommended: boolean = false;
    order: number = 0;
    status: number = 0;
    transaction_count: number = 0;
    transaction: TransactionModel[] = [];

    publisher: PublisherModel = new PublisherModel
    category: CategoryModel = new CategoryModel();
    authors: AuthorModel[] = [];
}