import { Controller } from '@hotwired/stimulus';

/**
 * Affiche / masque le texte du champ mot de passe (bouton œil).
 */
export default class extends Controller {
    static targets = ['input', 'iconShow', 'iconHide'];

    connect() {
        this._syncIcons();
    }

    toggle() {
        const input = this.inputTarget;
        input.type = input.type === 'password' ? 'text' : 'password';
        this._syncIcons();
    }

    _syncIcons() {
        const masked = this.inputTarget.type === 'password';
        this.iconShowTarget.hidden = !masked;
        this.iconHideTarget.hidden = masked;

        const btn = this.element.querySelector('.password-input-wrap__toggle');
        if (btn) {
            btn.setAttribute('aria-label', masked ? 'Afficher le mot de passe' : 'Masquer le mot de passe');
            btn.setAttribute('aria-pressed', masked ? 'false' : 'true');
        }
    }
}
