function Accounts(accounts = null, year_id = null){
    // this.accounts = accounts;
    this.accounts = [];
    this.year_id = year_id ? year_id : null;
    if(accounts){
        for (let i = 0; i < accounts.length; i++) {
            // this.accounts.push(new Account(accounts[i], null, this.year_id));
            this.accounts.push(new Account(accounts[i], this, this.year_id));
        }
    }
    
    this.get = function(index, new_instance = false){
        if(new_instance){
            return new Account(this.accounts[index], this, this.year_id);
        }
        return this.accounts[index];
    };

    this.size = function() {
        return this.accounts.length;
    }

    this.find = function(value, column = 'id', get_all = false){
        let results = this.accounts.filter(function(account){
            return account.get(column) == value;
        });
        if(get_all){
            return results;
        }else{
            return results[0];
        }
    };

    this.startsWith = function(value, column = 'id', get_all = false){
        let results = this.accounts.filter(function(account){
            let col = account.get(column) + '';
            return col.startsWith(value);
        });
        if(get_all){
            return results;
        }else{
            return results[0];
        }
    };

    this.assets = function() {
        return this.find(Account.ACCOUNT_ASSETS);
    }
    this.fixedAssets = function() {
        return this.find(Account.ACCOUNT_FIXED_ASSETS);
    }
    this.currentAssets = function() {
        return this.find(Account.ACCOUNT_CURRENT_ASSETS);
    }
    this.safes = function() {
        return this.find(Account.ACCOUNT_SAFES);
    }
    this.cashes = function() {
        return this.find(Account.ACCOUNT_CASHES);
    }
    this.banks = function() {
        return this.find(Account.ACCOUNT_BANKS);
    }
    this.customers = function() {
        return this.find(Account.ACCOUNT_CUSTOMERS);
    }
    this.liabilities = function() {
        return this.find(Account.ACCOUNT_LIABILITIES);
    }
    this.owners = function() {
        return this.find(Account.ACCOUNT_OWNERS);
    }
    this.capital = function() {
        return this.find(Account.ACCOUNT_CAPITAL);
    }
    this.expenses = function() {
        return this.find(Account.ACCOUNT_EXPENSES);
    }
    this.expense = function() {
        return this.find(Account.ACCOUNT_EXPENSE);
    }

    this.revenues = function() {
        return this.find(Account.ACCOUNT_REVENUES);
    }
    this.revenue = function() {
        return this.find(Account.ACCOUNT_REVENUE);
    }
    this.finals = function() {
        return this.find(Account.ACCOUNT_FINALS);
    }
}
