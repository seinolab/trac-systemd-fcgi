#!/usr/bin/python3
# -*- coding: utf-8 -*-
#
# Copyright (C) 2003-2009 Edgewall Software
# Copyright (C) 2003-2004 Jonas Borgström <jonas@edgewall.com>
# All rights reserved.
#
# This software is licensed as described in the file COPYING, which
# you should have received as part of this distribution. The terms
# are also available at https://trac.edgewall.org/wiki/TracLicense.
#
# This software consists of voluntary contributions made by many
# individuals. For the exact contribution history, see the revision
# history and logs, available at https://trac.edgewall.org/log/.
#
# Author: Jonas Borgström <jonas@edgewall.com>

from __future__ import print_function
import os
import sys
import setproctitle
# import pkg_resources
from trac.web.main import dispatch_request
from trac.web._fcgi import WSGIServer

def dispatch_fcgi(sockaddr):
    try:
        if 'PYTHON_EGG_CACHE' not in os.environ:
            if 'TRAC_ENV' in os.environ:
                egg_cache = os.path.join(os.environ['TRAC_ENV'], '.egg-cache')
            elif 'TRAC_ENV_PARENT_DIR' in os.environ:
                egg_cache = os.path.join(os.environ['TRAC_ENV_PARENT_DIR'],
                                         '.egg-cache')
            # pkg_resources.set_extraction_path(egg_cache)
        fcgiserv = WSGIServer(dispatch_request, bindAddress=sockaddr, umask=7)
        fcgiserv.run()

    except SystemExit:
         raise

    except Exception as e:
        print("Content-Type: text/plain\r\n\r\n", end=' ')
        print("Oops...")
        print()
        print("Trac detected an internal error:")
        print()
        print(e)
        print()
        import traceback
        import io
        tb = io.Bytes()
        traceback.print_exc(file=tb)
        print(tb.getvalue())

def main():
    if 'TRAC_PROCESS_TITLE' in os.environ:
        setproctitle.setproctitle(os.environ['TRAC_PROCESS_TITLE'])
    dispatch_fcgi(os.environ['TRAC_LISTEN_SOCKET'])

if __name__ == "__main__":
    main()
