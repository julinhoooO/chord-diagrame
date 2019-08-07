class ExpandableFab {
    constructor(trigger) {
        this.opened = false;
        this.trigger = trigger;
        this.target = document.querySelector(this.trigger.dataset.target);
        this.optionsFab = this.target.children;

        this.trigger.addEventListener("click", event => {
            event.stopPropagation();
            this.triggerFab();
        });

        document.body.addEventListener("click", event => {
            event.stopPropagation();
            if (this.opened) {
                this.triggerFab();
            } else {
                return false;
            }
        });

        for (let index = 0; index < this.optionsFab.length; index++) {
            this.optionsFab[index].addEventListener("click", event => {
                this.close();
            });
        }
    }
    triggerFab() {
        if (this.opened) {
            this.close();
        } else {
            this.open();
        }
    }
    open() {
        this.opened = true;
        this.trigger.classList.add("mdc-fab-expandable--expanded");
        for (let index = 0; index < this.optionsFab.length; index++) {
            var bottomPosition = (((this.optionsFab.length - index) * 45) + 45) * -1;
            anime({
                targets: this.optionsFab[index],
                translateY: bottomPosition,
                duration: 150,
                easing: 'easeOutBack'
            });
        }
    }
    close() {
        this.opened = false;
        this.trigger.classList.remove("mdc-fab-expandable--expanded");
        for (let index = 0; index < this.optionsFab.length; index++) {
            anime({
                targets: this.optionsFab[index],
                translateY: -40,
                easing: 'easeInBack',
                duration: 150
            });
        }
    }
}