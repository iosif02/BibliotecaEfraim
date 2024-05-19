import { defineStore } from "pinia";
import axios from "axios"
import HomepageViewModel from "@/models/book/HomepageViewModel";
import SearchBookModel from "@/models/book/SearchBookModel";
import BookModel from "@/models/book/BookModel";
import type DelayedBookModel from "@/models/book/DelayedBookModel";
import type BorrowBookModel from "@/models/book/BorrowBookModel";
import NotificationHelper from "@/helpers/NotificationHelper";

export const useBooksStore = defineStore('useBooksStore', {
  state: () => ({
    isLoading: false,
    isLoadingTwo: false,

		homepage: {
      searchModel: new SearchBookModel(),
      data: new HomepageViewModel(),
      isFetched: false
    },
    popularBooks: {
      searchModel: new SearchBookModel(),
      data: [] as BookModel[]
    },
    delayedBooks: {
      searchModel: new SearchBookModel(),
      data: [] as DelayedBookModel[]
    },
    categoryBooks: {
      searchModel: new SearchBookModel(),
      data: [] as BookModel[]
    },
    authorBooks: {
      searchModel: new SearchBookModel(),
      data: [] as BookModel[]
    },
    publisherBooks: {
      searchModel: new SearchBookModel(),
      data: [] as BookModel[]
    },
    userBorrowedBooks: {
      searchModel: new SearchBookModel(),
      data: [] as DelayedBookModel[]
    },
    userHistoryBooks: {
      searchModel: new SearchBookModel(),
      data: [] as DelayedBookModel[]
    },
    books: {
      searchModel: new SearchBookModel(),
      data: [] as BookModel[]
    }, 
    bookDetails: new BookModel(),
	}),
  getters: {

  },
  actions: {
    fetchHomepage() {
      this.isLoading = true;
      axios.get("/books/homepage")
      .then(result => {
          if(!result.data) return;

          this.homepage.data.categories = result.data?.categories?.data;
          this.homepage.data.popularBooks = result.data?.popularBooks?.data;
          this.homepage.data.delayedBooks = result.data?.delayedBooks?.data;
          this.homepage.data.totalDelayedBooks = result.data?.delayedBooks?.total;
          this.homepage.data.totalPopularBooks = result.data?.popularBooks?.total;
          this.homepage.data.totalCategoryBooks = result.data?.categories?.total;
          this.homepage.isFetched = true;
      })
      .catch(error => console.error("Request error: " + error))
      .finally(() => this.isLoading = false);
    },
    fetchPopularBooks() {
      this.isLoading = true;
      axios.post("/books/popular-books", this.popularBooks.searchModel)
      .then(result => {
          if(!result.data) return;

          this.popularBooks.data = result.data.data;
          this.popularBooks.searchModel.pagination.total = result.data.total ?? 1;
          this.popularBooks.searchModel.pagination.last_page = result.data.last_page ?? 1;
      })
      .catch(error => console.error("Request error: " + error))
      .finally(() => this.isLoading = false);
    },
    fetchDelayedBooks() {
      this.isLoading = true;
      axios.post("/books/delayed-books", this.delayedBooks.searchModel)
      .then(result => {
          if(!result.data) return;

          this.delayedBooks.data = result.data.data;
          this.delayedBooks.searchModel.pagination.total = result.data.total ?? 1;
          this.delayedBooks.searchModel.pagination.last_page = result.data.last_page ?? 1;
      })
      .catch(error => console.error("Request error: " + error))
      .finally(() => this.isLoading = false);
    },
    searchHomeBooks() {
      this.isLoading = true;
      axios.post("/books/search-global", this.homepage.searchModel)
      .then(result => {
          if(!result.data) return;

          this.homepage.data.books = result.data.data;
          this.homepage.searchModel.pagination.total = result.data.total ?? 1;
          this.homepage.searchModel.pagination.last_page = result.data.last_page ?? 1;
      })
      .catch(error => console.error("Request error: " + error))
      .finally(() => this.isLoading = false);
    },
    searchBooks() {
      this.isLoading = true;
      axios.post("/books/search", this.books.searchModel)
      .then(result => {
          if(!result.data) return;

          this.books.data = result.data.data;
          this.books.searchModel.pagination.total = result.data.total ?? 1;
          this.books.searchModel.pagination.last_page = result.data.last_page ?? 1;
      })
      .catch(error => console.error("Request error: " + error))
      .finally(() => this.isLoading = false);
    },
    async fetchBookDetails(bookId: String) {
      this.isLoading = true;
      return axios.get("/books/" + bookId)
      .then(result => {
          if(!result.data) return;

          this.bookDetails = result.data;
      })
      .catch(error => console.error("Request error: " + error))
      .finally(() => this.isLoading = false);
    },
    searchCategoryBooks() {
      this.isLoading = true;
      axios.post("/books/search", this.categoryBooks.searchModel)
      .then(result => {
          if(!result.data) return;

          this.categoryBooks.data = result.data.data;
          this.categoryBooks.searchModel.pagination.total = result.data.total ?? 1;
          this.categoryBooks.searchModel.pagination.last_page = result.data.last_page ?? 1;
      })
      .catch(error => console.error("Request error: " + error))
      .finally(() => this.isLoading = false);
    },
    searchAuthorBooks() {
      this.isLoading = true;
      axios.post("/books/search", this.authorBooks.searchModel)
      .then(result => {
          if(!result.data) return;

          this.authorBooks.data = result.data.data;
          this.authorBooks.searchModel.pagination.total = result.data.total ?? 1;
          this.authorBooks.searchModel.pagination.last_page = result.data.last_page ?? 1;
      })
      .catch(error => console.error("Request error: " + error))
      .finally(() => this.isLoading = false);
    },
    searchPublisherBooks() {
      this.isLoading = true;
      axios.post("/books/search", this.publisherBooks.searchModel)
      .then(result => {
          if(!result.data) return;

          this.publisherBooks.data = result.data.data;
          this.publisherBooks.searchModel.pagination.total = result.data.total ?? 1;
          this.publisherBooks.searchModel.pagination.last_page = result.data.last_page ?? 1;
      })
      .catch(error => console.error("Request error: " + error))
      .finally(() => this.isLoading = false);
    },
    searchUserBorrowedBooks() {
      this.isLoading = true;
      axios.post("/users/search-borrowed-books", this.userBorrowedBooks.searchModel)
      .then(result => {
          if(!result.data) return;

          this.userBorrowedBooks.data = result.data.data;
          this.userBorrowedBooks.searchModel.pagination.total = result.data.total ?? 1;
          this.userBorrowedBooks.searchModel.pagination.last_page = result.data.last_page ?? 1;
      })
      .catch(error => console.error("Request error: " + error))
      .finally(() => this.isLoading = false);
    },
    searchUserHistoryBooks() {
      this.isLoadingTwo = true;
      axios.post("/users/search-borrowed-books", this.userHistoryBooks.searchModel)
      .then(result => {
          if(!result.data) return;

          this.userHistoryBooks.data = result.data.data;
          this.userHistoryBooks.searchModel.pagination.total = result.data.total ?? 1;
          this.userHistoryBooks.searchModel.pagination.last_page = result.data.last_page ?? 1;
      })
      .catch(error => console.error("Request error: " + error))
      .finally(() => this.isLoadingTwo = false);
    },
    async createBook(book: FormData) {
      this.isLoading = true;
      return axios.post("/books/add", book)
      .then(result => {
        NotificationHelper.NotifySuccess('Book was created with scucces!')
        return result.data;
      })
      .catch(error => console.error("Request error: " + error))
      .finally(() => this.isLoading = false);
    },
    async updateBook(book: FormData) {
      this.isLoading = true;
      return axios.post("/books/update", book)
      .then(result => {
        NotificationHelper.NotifySuccess('Book was edited with scucces!')
        return result.data;
      })
      .catch(error => console.error("Request error: " + error))
      .finally(() => this.isLoading = false);
    },
    async deleteBook(bookId: Number) {
      this.isLoading = true;
      return axios.delete("/books/delete/" + bookId)
      .then(result => {
        NotificationHelper.NotifySuccess('Book was deleted with scucces!')
        return result.data;
      })
      .catch(error => console.error("Request error: " + error))
      .finally(() => this.isLoading = false);
    },
    async returnBook(transactionId: Number){
      this.isLoading = true;
      return axios.post("/books/return/" + transactionId)
      .then(result => {
        NotificationHelper.NotifySuccess('Book was returned with scucces!')
        return result.data;
      })
      .catch(error => console.error("Request error: " + error))
      .finally(() => this.isLoading = false);
    },
    async extendBook(transactionId: Number){
      this.isLoading = true;
      return axios.post("/books/extend/" + transactionId)
      .then(result => {
        NotificationHelper.NotifySuccess('Book was extended with scucces!')
        return result.data;
      })
      .catch(error => console.error("Request error: " + error))
      .finally(() => this.isLoading = false);
    },
    async borrowBook(borrowModel: BorrowBookModel) {
      this.isLoading = true;
      return axios.post("/books/borrow", borrowModel)
      .then(result => {
        NotificationHelper.NotifySuccess('Book was borrowed with scucces!')
        return result.data;
      })
      .catch(error => console.error("Request error: " + error))
      .finally(() => this.isLoading = false);
    },
  },
})