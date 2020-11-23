var Account = function (data = null, accounts = null, year_id = null) {
    this.data = data ? data : null;
    this.accounts = accounts ? accounts : null;
    this.get = function(column = 'id'){
        let columns = column.split('.');
        let data = this.data;
        columns.forEach(function(col){
            data = data[col];
        })
        return data;
    };

    this.getId = function(){
        return this.get('id');
    };
    this.getName = function(){
        return this.get('name');
    };
    
    this.children = function(only_direct = true){
        if(only_direct){
            return this.accounts.find(this.getId(), 'main_account', true);
        }else{
            return this.accounts.startsWith(this.getId(), 'number', true);
        }
    };

    this.parent = function(){
        return this.accounts.find(this.get('main_account'), 'id');
    };
    this.parents = function(){
        let parents = [];
        let parent = this.parent();
        while (parent) {
            parents.push(parent);
            parent = parent.parent();

        }
        return parents;
    };
    // if (data.children) {
    //     this.children = new Accounts(data.children, this.year_id);
    // }
    
    this.entries = new Entries();
    if (data.entries) {
        /*
        for (let i = 0; i < data.entries.length; i++) {
            if (this.year_id) {
                if (data.entries[i].year_id  == this.year_id) {
                    this.entries.push(new Entry(data.entries[i]));
                }
            }else{
                this.entries.push(new Entry(data.entries[i]));
            }
        }
        */

        let entries = data.entries;
        if(this.year_id){
            const year_id = this.year_id;
            entries = data.entries.filter(function (el) {
                return el.year_id == year_id;
            })
        }
        this.entries = new Entries(entries);
    }

    // this.children = data.children ? data.children : null;

    this.debts = function (year_id = null) {
        year_id = year_id ? year_id : this.year_id;
        let debts = this.entries.entries.filter(function (el) {
            return el.isDebt();
        });
        return new Entries(debts);
    }

    this.credits = function (year_id = null) {
        year_id = year_id ? year_id : this.year_id;
        let credits = this.entries.entries.filter(function (el) {
            return el.isCredit();
        });
        return new Entries(credits);
    }

    this.balance = function (formatted = false, year_id = null) {
        year_id = year_id ? year_id : this.year_id;

        let debts = this.debts(year_id);
        let credits = this.credits(year_id);
        if (debts && credits) {
            // balance = this.isDebt() ? debts.sumAmounts() - credits.sumAmounts() : credits.sumAmounts() - debts.sumAmounts();
            balance = Math.abs(credits.sumAmounts() - debts.sumAmounts());
            return formatted ? number_format(balance) : balance;
        }
        return 0;
    }

    this.balances = function (formatted = false, year_id = null) {
        year_id = year_id ? year_id : this.year_id;

        balances = this.balance(false, year_id);
        this.children(false).forEach(function (child){
            balances += child.balance(false, year_id);
        });
        return formatted ? number_format(balances) : balances;
    }

    this.getType = function () {
        return this.get('type');
    };

    this.displayType = function () {
        switch (this.get('type')) {
            case Account.TYPE_PRIMARY:
                return 'حساب رئيسي';
            case Account.TYPE_SECONDARY:
                return 'حساب فرعي';
            default:
                return '';
        }
    };

    this.getSide = function () {
        return this.side;
    };

    this.displaySide = function () {
        switch (this.side) {
            case Account.SIDE_DEBT:
                return 'مدين';
            case Account.SIDE_CREDIT:
                return 'دائن';
            default:
                return '';
        }
    };

    this.isPrimary = function () {
        return this.get('type') == Account.TYPE_PRIMARY;
    };

    this.getChildren = function () {
        return this.children;
        /*
        let accounts = [];
        for(let i = 0; i < this.children.length; i++){
            accounts.push(new Account(this.children[i]));
        }
        return accounts;
        */
    };

    this.isSecondary = function () {
        return this.get('type') == Account.TYPE_SECONDARY;
    };

    this.isDebt = function () {
        return this.side == Account.SIDE_DEBT;
    };

    this.isCredit = function () {
        return this.side == Account.SIDE_CREDIT;
    };
}

Account.TYPE_PRIMARY = 1;
Account.TYPE_SECONDARY = 0;
Account.TYPES = [
    Account.TYPE_SECONDARY,
    Account.TYPE_PRIMARY,
];

Account.SIDE_DEBT = 0;
Account.SIDE_CREDIT = 1;
Account.SIDES = [
    Account.SIDE_DEBT,
    Account.SIDE_CREDIT,
];

Account.ACCOUNT_ASSETS = 1;
Account.ACCOUNT_FIXED_ASSETS = 11;
Account.ACCOUNT_CURRENT_ASSETS = 12;

Account.ACCOUNT_SAFES = 121;
Account.ACCOUNT_CASHES = 1211;
Account.ACCOUNT_BANKS = 1212;

Account.ACCOUNT_CUSTOMERS = 122;

Account.ACCOUNT_LIABILITIES = 2;

Account.ACCOUNT_OWNERS = 3;
Account.ACCOUNT_CAPITAL = 31;

Account.ACCOUNT_EXPENSES = 4;
Account.ACCOUNT_EXPENSE = 41;

Account.ACCOUNT_REVENUES = 5;
Account.ACCOUNT_REVENUE = 51;
Account.ACCOUNT_FINALS = 6;

Account.DEFAULTS = [
    Account.ACCOUNT_ASSETS,
    Account.ACCOUNT_FIXED_ASSETS,
    Account.ACCOUNT_CURRENT_ASSETS,
    Account.ACCOUNT_SAFES,
    Account.ACCOUNT_CASHES,
    Account.ACCOUNT_BANKS,
    Account.ACCOUNT_CUSTOMERS,
    Account.ACCOUNT_LIABILITIES,
    Account.ACCOUNT_OWNERS,
    Account.ACCOUNT_CAPITAL,
    Account.ACCOUNT_EXPENSES,
    Account.ACCOUNT_REVENUES,
    Account.ACCOUNT_FINALS,
];