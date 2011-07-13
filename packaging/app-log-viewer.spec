
Name: app-log-viewer
Group: ClearOS/Apps
Version: 5.9.9.3
Release: 1%{dist}
Summary: Log Viewer
License: GPLv3
Packager: ClearFoundation
Vendor: ClearFoundation
Source: %{name}-%{version}.tar.gz
Buildarch: noarch
Requires: %{name}-core = %{version}-%{release}
Requires: app-base

%description
Log Viewer description

%package core
Summary: Log Viewer - APIs and install
Group: ClearOS/Libraries
License: LGPLv3
Requires: app-base-core

%description core
Log Viewer description

This package provides the core API and libraries.

%prep
%setup -q
%build

%install
mkdir -p -m 755 %{buildroot}/usr/clearos/apps/log_viewer
cp -r * %{buildroot}/usr/clearos/apps/log_viewer/


%post
logger -p local6.notice -t installer 'app-log-viewer - installing'

%post core
logger -p local6.notice -t installer 'app-log-viewer-core - installing'

if [ $1 -eq 1 ]; then
    [ -x /usr/clearos/apps/log_viewer/deploy/install ] && /usr/clearos/apps/log_viewer/deploy/install
fi

[ -x /usr/clearos/apps/log_viewer/deploy/upgrade ] && /usr/clearos/apps/log_viewer/deploy/upgrade

exit 0

%preun
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-log-viewer - uninstalling'
fi

%preun core
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-log-viewer-core - uninstalling'
    [ -x /usr/clearos/apps/log_viewer/deploy/uninstall ] && /usr/clearos/apps/log_viewer/deploy/uninstall
fi

exit 0

%files
%defattr(-,root,root)
/usr/clearos/apps/log_viewer/controllers
/usr/clearos/apps/log_viewer/htdocs
/usr/clearos/apps/log_viewer/views

%files core
%defattr(-,root,root)
%exclude /usr/clearos/apps/log_viewer/packaging
%exclude /usr/clearos/apps/log_viewer/tests
%dir /usr/clearos/apps/log_viewer
/usr/clearos/apps/log_viewer/deploy
/usr/clearos/apps/log_viewer/language
/usr/clearos/apps/log_viewer/libraries
