var Entry = function(data = null) {
    this.data = data ? data : null;
    this.get = function(column = 'id'){
        let columns = column.split('.');
        let data = this.data;
        columns.forEach(function(col){
            data = data[col];
        })
        return data;
    };

    this.accounts = function () {
        let accounts = this.get('accounts');
        if (accounts) {
            return this.accounts.find(this.getId(), 'main_account', true);
        } 
    };

    

    this.isDebt = function () {
        return this.get('pivot.side') == Entry.SIDE_DEBTS;
    };

    this.isCredit = function () {
        return this.get('pivot.side') == Entry.SIDE_CREDITS;
    };

    this.getSide = function () {
        let side = this.get('pivot.side');
        return side;
    };

    this.getYearId = function () {
        let side = this.data.year_id;
        return side;
    };

    this.getAmount = function (formated = false) {
        let amount = this.get('pivot.amount');
        if (formated) number_format(amount);
        return amount;
    };

}

Entry.SIDE_DEBTS   = 1;
Entry.SIDE_CREDITS = 2;
Entry.SIDES = [
    Entry.SIDE_DEBTS,
    Entry.SIDE_CREDITS,
];