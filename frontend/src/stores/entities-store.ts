import { defineStore } from "pinia";
import axios from "axios"
import type AuthorModel from "@/models/entities/AuthorModel";
import SearchAuthorModel from "@/models/entities/SearchAuthorModel";
import type PublisherModel from "@/models/entities/PublisherModel";
import SearchPublisherModel from "@/models/entities/SearchPublisherModel";
import type CategoryModel from "@/models/book/CategoryModel";
import SearchCategoryModel from "@/models/entities/SearchCategoryModel";

export const useEntitiesStore = defineStore('useEntitiesStore', {
    state: () => ({
        isLoading: false,

        authors: {
            data: [] as AuthorModel[],
            searchModel: new SearchAuthorModel()
        },
        publishers: {
            data: [] as PublisherModel[],
            searchModel: new SearchPublisherModel()
        },
        categories: {
            data: [] as CategoryModel[],
            searchModel: new SearchCategoryModel(),
        },
        entities: {
            publishers: [] as PublisherModel[],
            authors: [] as AuthorModel[],
            categories: [] as CategoryModel[],
        }
    }),
    getters: {

    },
    actions: {
        async fetchAuthors() {
            try {
                let authors = await axios.post("/entities/authors/search", this.authors.searchModel);
                if(authors?.data) {
                    this.authors.data = authors.data.data;
                    this.authors.searchModel.pagination.total = authors.data.total ?? 1;
                    this.authors.searchModel.pagination.last_page = authors.data.last_page ?? 1;
                }
            } catch(ex) {
                console.error("Request error: " + ex);
            }
        },
        async fetchPublishers() {
            try {
                let publishers = await axios.post("/entities/publishers/search", this.publishers.searchModel);
                if(publishers?.data) {
                    this.publishers.data = publishers.data.data;
                    this.publishers.searchModel.pagination.total = publishers.data.total ?? 1;
                    this.publishers.searchModel.pagination.last_page = publishers.data.last_page ?? 1;
                }
            } catch(ex) {
                console.error("Request error: " + ex);
            }
          },
          async fetchCategories() {
            try {
                let books = await axios.post("/entities/categories/search", this.categories.searchModel);
                if(books?.data) {
                    this.categories.data = books.data.data;
                    this.categories.searchModel.pagination.total = books.data.total ?? 1;
                    this.categories.searchModel.pagination.last_page = books.data.last_page ?? 1;
                }
            } catch(ex) {
              console.error("Request error: " + ex);
            }
        },
        fetchEntities() {
            this.isLoading = true;
            axios.get("/entities")
            .then(result => {
                if(!result.data) return;

                this.entities.publishers = result.data?.publishers;
                this.entities.authors = result.data?.authors;
                this.entities.categories = result.data?.categories;
            })
            .catch(error => console.error("Request error: " + error))
            .finally(() => this.isLoading = false);
        },
        categoriesChangePage(page: number) {
            this.categories.searchModel.pagination.page = page;
        },
    }
})