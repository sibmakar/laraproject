class BirdBoardForm {
    constructor(data){
        this.originalData = {};
        this.errors  = {};

        this.submitted = false;

        //Object.assign(this.originalData, data);

        this.originalData = JSON.parse(JSON.stringify(data));

        Object.assign(this, data);
    }

    data() {

        return Object.keys(this.originalData).reduce((data, attribute) => {
            data[attribute] = this[attribute];

            return data;
        }, {});

    }

    submit(endpoint, requestType = 'post'){
            return axios[requestType](endpoint, this.data())
                .catch(this.onFail.bind(this))
                .then(this.onSuccess.bind(this));
    }


    patch(endpoint) {
        this.submit(endpoint, 'patch');
    }

    delete(endpoint) {
        this.submit(endpoint, 'delete');
    }

    post(endpoint) {
        this.submit(endpoint);
    }






    onSuccess(response) {

        this.submitted = true;

        this.errors = {};

        return response;
    }

    onFail(error) {
        this.errors = error.response.data.errors;

        this.submitted = false;

        throw error;
    }

    reset() {
        Object.assign(this, this.originalData);
    }
}

export default BirdBoardForm;
