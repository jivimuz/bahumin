import '@hotwired/turbo';
import { initAjaxLists } from './ajax-list';

document.addEventListener('turbo:load', initAjaxLists);
