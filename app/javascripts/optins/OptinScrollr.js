class OptinScrollr{
    constructor(trigger, reverse = false, cb){
        this.trigger = trigger + ''; // Cast to string
        this.toggleState = false;
        this.cb = cb;
        this.reverse = reverse;
        this.self = self;
        this.scrollSmart = this.isSmart(trigger); // We only show when the user scroll up

        this.latestKnownScrollY = 0;
        this.latestScrollY = 0;
        this.ticking = false;
        this.toScroll = 0;

        window.addEventListener('scroll', this.scrollDispatch.bind(this), false);
    }

    isPercent(text){
        return text.match(/\d+%?/);
    }

    isSmart(text){
        return text.match(/^smart(?!\w)/)
    }

    percentScrollPx(percent){
        return (document.documentElement['scrollHeight'] - document.documentElement.clientHeight) * parseInt(percent) / 100;
    }

    elementScrollPx(el){
        var element = document.querySelector(el);
        if(element == null){
          return false
        }
        return element.offsetTop;
    }


    scrollDispatch(){
        this.latestKnownScrollY = window.scrollY;
        this.requestTick();
    }

    requestTick() {
        if(!this.ticking) {
            // We check the distance to scroll in px here because the user could have resize the window
            if (this.isPercent(this.trigger)) {
                this.toScroll = this.percentScrollPx(this.trigger);
            }
            else if(!this.isSmart(this.trigger)){
                this.toScroll = this.elementScrollPx(this.trigger);
            }

            requestAnimationFrame(this.update.bind(this));
        }
        this.ticking = true;
    }

    update(){
        if (this.isSmart(this.trigger)) {
            if (this.latestScrollY > this.latestKnownScrollY && !this.toggleState) { // We are scrolling to the top we show the bar
                this.toggleState = true;
                this.cb(this.toggleState);
            }
            else if(this.latestScrollY < this.latestKnownScrollY && this.toggleState){
                this.toggleState = false;
                this.cb(this.toggleState);
            }
        }else{
            if(this.latestKnownScrollY > this.toScroll && !this.toggleState){ // We only toggle the state once
                this.toggleState = true;
                this.cb(this.toggleState);
            }
            else if(this.latestKnownScrollY < this.toScroll && this.reverse && this.toggleState){
                this.toggleState = false;
                this.cb(this.toggleState);
            }
        }

        // reset the tick so we can
        // capture the next onScroll
        this.latestScrollY = this.latestKnownScrollY; // Needed for smartbar functionnality
        this.ticking = false;
    }

}

export default OptinScrollr;
