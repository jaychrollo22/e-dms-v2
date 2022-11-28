<script>
export default {
	data() {
		return {
			//pagination =====
			pagination: {},
			page_limit: 5,
			currentPageToGo: 1,

			//list ===========
			listEndpoint: 'all',
			items: [],
			filterData: {},
			keyTimeout: null,
			isProcessing: false,

			//requests =======
			formAction: 'add',
			defaultDataValue: {},
			errors: {},
			requestProcessing: false,
			activeModal: ''
		}
	},
	created() {
		this.cloneDefaultFormdata();
	},
	methods: {
		fetchList(endpoint, list = null) {
			let link = endpoint ? endpoint : `${this.endpoint}/${this.listEndpoint}`
			let collection = list ? list : 'items';
			this.isProcessing = true;
			axios.get(link, {
				params: {
					page: this.currentPageToGo,
					limit: this.page_limit,
					...this.filterData
				}
			})
				.then(response => {
					const { data, ...rest } = response.data
					this[collection] = data;

					this.pagination = rest;
					this.isProcessing = false;
					this.currentPageToGo = 1

					//Set pagination page count
					this.setPaginationPageRange(this.pagination.current_page, this.pagination.last_page);

					if (this.autoShow) {
						if (this[collection].length == 1) {
							this.viewDocument(this[collection][0])
						}
					}

				})
				.catch(error => {
					if (error.response.status === 422) {
						this.errors = error.response.data.errors;
						alert('Please check all fields.', 'Error');
					} else {
						alert('API error: Contact administrator.', 'API Error');
					}
					this.isProcessing = false;
				});
		},
		// submitData(endpoint, data, fetchEndpoint = null) {
		// 	//return if already processing to prevent duplicate submission
		// 	if (this.requestProcessing) return;

		// 	//Reset error
		// 	this.errors = [];

		// 	//start processing...
		// 	this.errors = {};
		// 	this.requestProcessing = true;
		// 	let method = this.formAction === 'add' ? 'POST' : 'PUT';

		// 	let updateEndpoint = [
		// 		'/document-creation/inbound/update',
		// 		'/document-receiving/inter-site/update',
		// 		'/document-sending/inter-office/update',
		// 		'/document-uploading/document-upload/update'
		// 	]

		// 	if (_.includes(updateEndpoint, endpoint) ) {

		//         if (this.formAction === 'edit') {
		//             method = 'POST';
		//         }
		//     }
		// 	if (this.endpoint === '/master-data/users') {
		// 		if (this.formAction === 'edit') {
		//             method = 'PATCH';
		//         }
		// 	}

		// 	let options = {
		//         method,
		//         url: endpoint,
		//         data
		//     }
		// 	axios(options)
		// 	.then( res => {
		// 		this.fetchList(fetchEndpoint);
		// 		this.closeModal();
		// 		this.requestProcessing = false;
		// 		toastr.success('Data submitted successfully.', 'Success');
		// 	})
		// 	.catch(error => {
		// 		if(error.response.status === 422) {
		// 			this.errors = error.response.data.errors;
		// 			toastr.error('Please check all fields.', 'Error');
		// 		} else {
		// 			toastr.error('API error: Contact administrator.', 'API Error');
		// 		}
		// 		this.requestProcessing = false;
		// 	});
		// },
		searchKeyUp() {
			this.isProcessing = true;
			clearTimeout(this.keyTimeout);
			this.keyTimeout = setTimeout(() => {
				this.fetchList();
			}, 500)
		},
		cloneDefaultFormdata() {
			this.defaultDataValue = JSON.stringify(this.data)
		},
		doResetData() {
			this.data = JSON.parse(this.defaultDataValue);
			this.errors = {};
			this.formAction = 'add';
		},

		// //Modal methods =============================
		// showModal(modalName) {
		// 	this.activeModal = modalName;
		// 	this.$modal.show(modalName);
		// },
		// closeModal() {
		// 	this.$modal.hide(this.activeModal);
		// 	this.activeModal = '';
		// 	this.doResetData();
		// },
		// //===========================================

		//Pagination methods =============================
		goToPage(page) {
			this.currentPageToGo = page;
			this.fetchList();
		},
		changePageCount(pageLimit) {
			this.page_limit = pageLimit;
			this.fetchList();
		},
		setPaginationPageRange(page, pageCount) {

			let start = page - 2,
				end = page + 2;

			if (end > pageCount) {
				start -= (end - pageCount);
				end = pageCount;
			}

			if (start <= 0) {
				end += ((start - 1) * (-1));
				start = 1;
			}

			end = end > pageCount ? pageCount : end;

			return this.pagination.range = Array(end - start + 1).fill().map((_, idx) => start + idx)

		},
		//================================================
	}
}
</script>