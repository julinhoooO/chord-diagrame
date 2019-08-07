class BottomSheet {
  constructor(trigger) {
    this.opened = false;
    this.minimized = false;
    this.trigger = trigger;
    const target = trigger.dataset.target;
    const classes = {
      opened: "bottom-sheet--opened",
      minimized: "bottom-sheet--minimized",
      opening: "bottom-sheet--opening",
      closing: "bottom-sheet--closing"
    };
    this.root_ = document.querySelector(target);
    this.container;
    var scrim;
    var actionGroup;
    for (let index = 0; index < this.root_.children.length; index++) {
      if (this.root_.children[index].classList.contains("bottom-sheet--container")) {
        this.container = this.root_.children[index];
        break;
      }
    }
    for (let index = 0; index < this.root_.children.length; index++) {
      if (this.root_.children[index].classList.contains("bottom-sheet--scrim")) {
        scrim = this.root_.children[index];
        break;
      }
    }
    for (let index = 0; index < this.root_.children.length; index++) {
      if (this.root_.children[index].classList.contains("bottom-sheet--action")) {
        actionGroup = this.root_.children[index];
        break;
      }
    }
    if (actionGroup.dataset.action) {
      const action = actionGroup.dataset.action;
      mdc.ripple.MDCRipple.attachTo((actionGroup));
      actionGroup.addEventListener("click", event => {
        event.stopPropagation();
        if (action != "open" && action != "close" && action != "minimize") {
          console.log("Only 'open', 'close' or 'minimize' action are allowed");
          return false;
        } else {
          this[action](classes);
        }
      });
    }
    this.trigger.addEventListener("click", event => {
      event.stopPropagation();
      if (!this.opened) this.open(classes);
      else this.close(classes);
    });
    scrim.addEventListener("click", event => {
      event.stopPropagation();
      if (this.opened) this.close(classes);
      return false;
    });
    [].slice
      .call(document.querySelectorAll(".acordeTrigger"))
      .forEach(ele => {
        ele.addEventListener("click", event => {
          event.stopPropagation();
          if (this.opened) this.close(classes);
        });
      });
  }

  open(classes) {
    this.opened = true;
    window.document.body.style.overflowY = "hidden";
    this.root_.classList.remove(classes.minimized);
    this.root_.classList.add(classes.opened);
    this.container.classList.remove(classes.closing);
    this.container.classList.add(classes.opening);
    setTimeout(() => {
      this.container.classList.remove(classes.opening);
    }, 160);
  }
  close(classes) {
    this.opened = false;
    window.document.body.style.overflowY = "auto";
    this.container.classList.remove(classes.opening);
    if (!this.minimized) {
      this.container.classList.add(classes.closing);
    }
    setTimeout(() => {
      this.root_.classList.remove(classes.opened);
      this.root_.classList.remove(classes.minimized);
      this.container.classList.remove(classes.closing);
    }, 160);
    this.minimized = false;
  }
  minimize(classes) {
    if (!this.minimized) {
      this.minimized = true;
      this.root_.classList.add(classes.minimized);
    } else {
      this.minimized = false;
      this.root_.classList.remove(classes.minimized);
    }
  }
}