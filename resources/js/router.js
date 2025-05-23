import vueRouter from 'vue-router';
import Vue from 'vue';
import store from '@/store'

Vue.use(vueRouter);

import Index from '@/pages/Index';

import SanctumRegister from '@/pages/Sanctum/Register';
import SanctumLogin from '@/pages/Sanctum/Login';

import AdminIndex from '@/pages/Admin/Index';

import AdminPostsIndex from '@/pages/Admin/Posts/Index';
import AdminPostsCreate from '@/pages/Admin/Posts/Create';
import AdminPostsEdit from '@/pages/Admin/Posts/Edit';
import AdminPostsTranslationsCreate from '@/pages/Admin/PostsTranslations/Create';
import AdminPostsTranslationsEdit from '@/pages/Admin/PostsTranslations/Edit';

import AdminPhotoreportageIndex from '@/pages/Admin/PhotoReportage/Index';
import AdminPhotoreportageCreate from '@/pages/Admin/PhotoReportage/Create';
import AdminPhotoreportageEdit from '@/pages/Admin/PhotoReportage/Edit';
import AdminPhotoReportageTranslateCreate from '@/pages/Admin/PhotoReportageTranslate/Create';
import AdminPhotoReportageTranslateEdit from '@/pages/Admin/PhotoReportageTranslate/Edit';

import AdminTagsIndex from '@/pages/Admin/Tags/Index';
import AdminTagsCreate from '@/pages/Admin/Tags/Create';
import AdminTagsEdit from '@/pages/Admin/Tags/Edit';

import AdminMaterialsIndex from '@/pages/Admin/Materials/Index';
import AdminMaterialsCreate from '@/pages/Admin/Materials/Create';
import AdminMaterialsEdit from '@/pages/Admin/Materials/Edit';

import AdminMaterialsIngIndex from '@/pages/Admin/MaterialsIng/Index';
import AdminMaterialsIngCreate from '@/pages/Admin/MaterialsIng/Create';
import AdminMaterialsIngEdit from '@/pages/Admin/MaterialsIng/Edit';

import AdminMaterialsTranslationsCreate from '@/pages/Admin/MaterialsTranslations/Create';
import AdminMaterialsTranslationsEdit from '@/pages/Admin/MaterialsTranslations/Edit';

import AdminCategoriesIndex from '@/pages/Admin/Categories/Index';
import AdminCategoriesCreate from '@/pages/Admin/Categories/Create';
import AdminCategoriesEdit from '@/pages/Admin/Categories/Edit';

import AdminAuthorsIndex from '@/pages/Admin/Authors/Index';
import AdminAuthorsCreate from '@/pages/Admin/Authors/Create';
import AdminAuthorsEdit from '@/pages/Admin/Authors/Edit';
import AdminAuthorsTranslationsCreate from '@/pages/Admin/AuthorsTranslations/Create';
import AdminAuthorsTranslationsEdit from '@/pages/Admin/AuthorsTranslations/Edit';

import AdminExpertsIndex from '@/pages/Admin/Experts/Index';
import AdminExpertsCreate from '@/pages/Admin/Experts/Create';
import AdminExpertsEdit from '@/pages/Admin/Experts/Edit';

import AdminFilesIndex from '@/pages/Admin/Files/Index';

import AdminUsersIndex from '@/pages/Admin/Users/Index';
import AdminUsersCreate from '@/pages/Admin/Users/Create';
import AdminUsersEdit from '@/pages/Admin/Users/Edit';

import AdminNewspapersIndex from '@/pages/Admin/Newspapers/Index';
import AdminNewspapersCreate from '@/pages/Admin/Newspapers/Create';
import AdminNewspapersEdit from '@/pages/Admin/Newspapers/Edit';

import AdminStatisticsIndex from '@/pages/Admin/Statistics/Index';
import AdminStatisticsMaterials from '@/pages/Admin/Statistics/Materials';

import AdminVideoArticlesIndex from '@/pages/Admin/VideoArticles/Index';
import AdminVideoArticlesCreate from '@/pages/Admin/VideoArticles/Create';
import AdminVideoArticlesEdit from '@/pages/Admin/VideoArticles/Edit';
import AdminVideoArticlesTranslationsCreate from '@/pages/Admin/VideoArticlesTranslations/Create';
import AdminVideoArticlesTranslationsEdit from '@/pages/Admin/VideoArticlesTranslations/Edit';

import AdminDocumentsIndex from '@/pages/Admin/Documents/Index';
import AdminDocumentsCreate from '@/pages/Admin/Documents/Create';
import AdminDocumentsEdit from '@/pages/Admin/Documents/Edit';
import AdminDocumentsTranslationsCreate from '@/pages/Admin/DocumentsTranslations/Create';
import AdminDocumentsTranslationsEdit from '@/pages/Admin/DocumentsTranslations/Edit';

import AdminLitsalonArticlesIndex from '@/pages/Admin/LitsalonArticles/Index';
import AdminLitsalonArticlesCreate from '@/pages/Admin/LitsalonArticles/Create';
import AdminLitsalonArticlesEdit from '@/pages/Admin/LitsalonArticles/Edit';
import AdminLitsalonArticlesTranslationsCreate from '@/pages/Admin/LitsalonArticlesTranslations/Create';
import AdminLitsalonArticlesTranslationsEdit from '@/pages/Admin/LitsalonArticlesTranslations/Edit';

const routes = [
    {name: 'index', path: '/', component: Index, meta: {layout: 'default'}},

    {name: 'sanctum-register', path: '/sanctum/register', component: SanctumRegister, meta: {layout: 'default', notAuth: true}},
    {name: 'sanctum-login', path: '/sanctum/login', component: SanctumLogin, meta: {layout: 'default', notAuth: true}},

    {name: 'admin-index', path: '/admin/', component: AdminIndex, meta: {layout: 'admin-default', auth: true}},

    {name: 'admin-posts-index', path: '/admin/posts/', component: AdminPostsIndex, meta: {layout: 'admin-default', auth: true, role: 'test'}},
    {name: 'admin-posts-create', path: '/admin/posts/create', component: AdminPostsCreate, meta: {layout: 'admin-default', auth: true, permisson: 'test'}},
    {name: 'admin-posts-edit', path: '/admin/posts/:id/edit', component: AdminPostsEdit, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-posts_translations-create', path: '/admin/posts/:post_id/posts_translations/create', component: AdminPostsTranslationsCreate, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-posts_translations-edit', path: '/admin/posts/posts_translations/:id/edit', component: AdminPostsTranslationsEdit, meta: {layout: 'admin-default', auth: true}},

    {name: 'admin-photoreportage-index', path: '/admin/photoreportage/', component: AdminPhotoreportageIndex, meta: {layout: 'admin-default', auth: true, role: 'test'}},
    {name: 'admin-photoreportage-create', path: '/admin/photoreportage/create', component: AdminPhotoreportageCreate, meta: {layout: 'admin-default', auth: true, permisson: 'test'}},
    {name: 'admin-photoreportage-edit', path: '/admin/photoreportage/:id/edit', component: AdminPhotoreportageEdit, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-photo_reportage_translations-create', path: '/admin/photoreportage/:reportage_id/photo_reportage_translations/create', component: AdminPhotoReportageTranslateCreate, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-photo_reportage_translations-edit', path: '/admin/photoreportage/photo_reportage_translations/:id/edit', component: AdminPhotoReportageTranslateEdit, meta: {layout: 'admin-default', auth: true}},



    {name: 'admin-tags-index', path: '/admin/tags', component: AdminTagsIndex, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-tags-create', path: '/admin/tags/create', component: AdminTagsCreate, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-tags-edit', path: '/admin/tags/:id/edit', component: AdminTagsEdit, meta: {layout: 'admin-default', auth: true}},

    {name: 'admin-materials-index', path: '/admin/materials/', component: AdminMaterialsIndex, meta: {layout: 'admin-default', auth: true, role: 'test'}},
    {name: 'admin-materials-create', path: '/admin/materials/create', component: AdminMaterialsCreate, meta: {layout: 'admin-default', auth: true, permisson: 'test'}},
    {name: 'admin-materials-edit', path: '/admin/materials/:id/edit', component: AdminMaterialsEdit, meta: {layout: 'admin-default', auth: true}},

    {name: 'admin-materials-inh-index', path: '/admin/materials-inh', component: AdminMaterialsIngIndex, meta: {layout: 'admin-default', auth: true, role: 'test'}},
    {name: 'admin-materials-inh-create', path: '/admin/materials-inh/create', component: AdminMaterialsIngCreate, meta: {layout: 'admin-default', auth: true, permisson: 'test'}},
    {name: 'admin-materials-inh-edit', path: '/admin/materials-inh/:id/edit', component: AdminMaterialsIngEdit, meta: {layout: 'admin-default', auth: true}},

    {name: 'admin-materials_translations-create', path: '/admin/materials/:material_id/materials_translations/create', component: AdminMaterialsTranslationsCreate, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-materials_translations-edit', path: '/admin/materials/materials_translations/:id/edit', component: AdminMaterialsTranslationsEdit, meta: {layout: 'admin-default', auth: true}},

    {name: 'admin-categories-index', path: '/admin/categories', component: AdminCategoriesIndex, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-categories-create', path: '/admin/categories/create', component: AdminCategoriesCreate, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-categories-edit', path: '/admin/categories/:id/edit', component: AdminCategoriesEdit, meta: {layout: 'admin-default', auth: true}},

    {name: 'admin-authors-index', path: '/admin/authors/', component: AdminAuthorsIndex, meta: {layout: 'admin-default', auth: true, role: 'test'}},
    {name: 'admin-authors-create', path: '/admin/authors/create', component: AdminAuthorsCreate, meta: {layout: 'admin-default', auth: true, permisson: 'test'}},
    {name: 'admin-authors-edit', path: '/admin/authors/:id/edit', component: AdminAuthorsEdit, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-authors_translations-create', path: '/admin/authors/:author_id/authors_translations/create', component: AdminAuthorsTranslationsCreate, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-authors_translations-edit', path: '/admin/authors/authors_translations/:id/edit', component: AdminAuthorsTranslationsEdit, meta: {layout: 'admin-default', auth: true}},


    {name: 'admin-experts-index', path: '/admin/experts/', component: AdminExpertsIndex, meta: {layout: 'admin-default', auth: true, role: 'test'}},
    {name: 'admin-experts-create', path: '/admin/experts/create', component: AdminExpertsCreate, meta: {layout: 'admin-default', auth: true, permisson: 'test'}},
    {name: 'admin-experts-edit', path: '/admin/experts/:id/edit', component: AdminExpertsEdit, meta: {layout: 'admin-default', auth: true}},


    {name: 'admin-files-index', path: '/admin/files', component: AdminFilesIndex, meta: {layout: 'admin-default', auth: true}},

    {name: 'admin-users-index', path: '/admin/users', component: AdminUsersIndex, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-users-create', path: '/admin/users/create', component: AdminUsersCreate, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-users-edit', path: '/admin/users/:id/edit', component: AdminUsersEdit, meta: {layout: 'admin-default', auth: true}},

    {name: 'admin-newspapers-index', path: '/admin/newspapers', component: AdminNewspapersIndex, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-newspapers-create', path: '/admin/newspapers/create', component: AdminNewspapersCreate, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-newspapers-edit', path: '/admin/newspapers/:id/edit', component: AdminNewspapersEdit, meta: {layout: 'admin-default', auth: true}},

    {name: 'admin-statistics-index', path: '/admin/statistics', component: AdminStatisticsIndex, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-statistics-materials', path: '/admin/statistics/materials', component: AdminStatisticsMaterials, meta: {layout: 'admin-default', auth: true}},

    {name: 'admin-video_articles-index', path: '/admin/video_articles/', component: AdminVideoArticlesIndex, meta: {layout: 'admin-default', auth: true, role: 'test'}},
    {name: 'admin-video_articles-create', path: '/admin/video_articles/create', component: AdminVideoArticlesCreate, meta: {layout: 'admin-default', auth: true, permisson: 'test'}},
    {name: 'admin-video_articles-edit', path: '/admin/video_articles/:id/edit', component: AdminVideoArticlesEdit, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-video_articles_translations-create', path: '/admin/video_articles/:video_article_id/video_articles_translations/create', component: AdminVideoArticlesTranslationsCreate, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-video_articles_translations-edit', path: '/admin/video_articles/video_articles_translations/:id/edit', component: AdminVideoArticlesTranslationsEdit, meta: {layout: 'admin-default', auth: true}},

    {name: 'admin-documents-index', path: '/admin/documents/', component: AdminDocumentsIndex, meta: {layout: 'admin-default', auth: true, role: 'test'}},
    {name: 'admin-documents-create', path: '/admin/documents/create', component: AdminDocumentsCreate, meta: {layout: 'admin-default', auth: true, permisson: 'test'}},
    {name: 'admin-documents-edit', path: '/admin/documents/:id/edit', component: AdminDocumentsEdit, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-documents_translations-create', path: '/admin/documents/:document_id/documents_translations/create', component: AdminDocumentsTranslationsCreate, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-documents_translations-edit', path: '/admin/documents/documents_translations/:id/edit', component: AdminDocumentsTranslationsEdit, meta: {layout: 'admin-default', auth: true}},

    {name: 'admin-litsalon_articles-index', path: '/admin/litsalon_articles/', component: AdminLitsalonArticlesIndex, meta: {layout: 'admin-default', auth: true, role: 'test'}},
    {name: 'admin-litsalon_articles-create', path: '/admin/litsalon_articles/create', component: AdminLitsalonArticlesCreate, meta: {layout: 'admin-default', auth: true, permisson: 'test'}},
    {name: 'admin-litsalon_articles-edit', path: '/admin/litsalon_articles/:id/edit', component: AdminLitsalonArticlesEdit, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-litsalon_articles_translations-create', path: '/admin/litsalon_articles/:litsalon_article_id/litsalon_articles_translations/create', component: AdminLitsalonArticlesTranslationsCreate, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-litsalon_articles_translations-edit', path: '/admin/litsalon_articles/litsalon_articles_translations/:id/edit', component: AdminLitsalonArticlesTranslationsEdit, meta: {layout: 'admin-default', auth: true}},
];

const router = new vueRouter({
    mode: 'history',
    routes
})

router.beforeEach((to, from, next) => {
    if (to.matched.some(m => m.meta.auth)) {
        if (!store.getters.isAuthenticated) {
            next('/sanctum/login')
        }
    }
    if (to.matched.some(m => m.meta.notAuth)) {
        if (store.getters.isAuthenticated) {
            next('/admin')
        }
    }
    /*
    if (to.matched.some(m => m.meta.role)) {
        console.log(to.meta.role)
        console.log(store.getters.getRoles)
        if ( !store.getters.getRoles || !store.getters.getRoles[to.meta.role] ) {
            next('/admin')
        }
    }
    if (to.matched.some(m => m.meta.permission)) {
        if ( !store.getters.getPermissions || !store.getters.getPermissions[to.meta.permission] ) {
            next('/admin')
        }
    }
    */
    return next()
})

export default router;
