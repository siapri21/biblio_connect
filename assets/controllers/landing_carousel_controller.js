import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['scroller'];

    next() {
        this.scrollerTarget.scrollBy({ left: 300, behavior: 'smooth' });
    }

    prev() {
        this.scrollerTarget.scrollBy({ left: -300, behavior: 'smooth' });
    }
}
