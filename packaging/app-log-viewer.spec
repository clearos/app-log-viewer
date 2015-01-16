
Name: app-log-viewer
Epoch: 1
Version: 2.0.14
Release: 1%{dist}
Summary: Log Viewer
License: GPLv3
Group: ClearOS/Apps
Source: %{name}-%{version}.tar.gz
Buildarch: noarch
Requires: %{name}-core = 1:%{version}-%{release}
Requires: app-base

%description
The Log Viewer app provides a tabular display of all system log files.  Viewing and searching of log files are essential and typically the first step in troubleshooting problems with your system.

%package core
Summary: Log Viewer - Core
License: LGPLv3
Group: ClearOS/Libraries
Requires: app-base-core
Requires: app-base >= 1:1.4.16

%description core
The Log Viewer app provides a tabular display of all system log files.  Viewing and searching of log files are essential and typically the first step in troubleshooting problems with your system.

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
%dir /usr/clearos/apps/log_viewer
/usr/clearos/apps/log_viewer/deploy
/usr/clearos/apps/log_viewer/language
/usr/clearos/apps/log_viewer/libraries
