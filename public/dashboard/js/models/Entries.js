var Entries = function(entries) {
    this.entries = [];
    if (entries) {
        if(entries[0] instanceof Entry){
            this.entries = entries;
        }else{
            for (let i = 0; i < entries.length; i++) {
                this.entries.push(new Entry(entries[i]));
            }
        }
    }

    this.sumAmounts = function (formated = false) {
        let amount = 0;
        this.entries.forEach(function(entry) {
            amount += entry.getAmount();
        });
        if (formated) return number_format(amount);
        return amount;
    };
}